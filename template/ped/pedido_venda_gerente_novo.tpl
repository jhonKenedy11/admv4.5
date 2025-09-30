<style>
    .form-control,
    .x_panel {
        border-radius: 5px;
    }

    .invis {
        display: none;
    }

    .checkBox {
        width: 2px;
        padding: 0;
        margin: center;
    }

    #btnEmissaoNf {
        width: 50px;
    }

    #btnFilter {
        font-size: 12px;
    }
</style>
<script type="text/javascript" src="{$pathJs}/ped/s_pedido_venda_gerente_novo.js"> </script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<!-- page content -->
<div class="right_col" role="main">

    <div class="">
        <div class="row">


            <!-- panel principal  -->
            <div class="col-md-12 col-xs-12">
                <div class="x_panel">
                    <h2>Gerencia de Pedidos<br>
                        <button id="btnFilter" type="button" class="btn btn-dark btn-xs dropdown-toggle"
                            onclick="javascript:submitTodosPedidosDia();">Mostrar Pedidos Dia</button>
                        <button id="btnFilter" type="button" class="btn btn-dark btn-xs dropdown-toggle"
                            onclick="javascript:submitTodosPedidosMes();">Mostrar Pedidos Mes</button>
                        <button id="btnFilter" type="button" class="btn btn-dark btn-xs dropdown-toggle"
                            onclick="javascript:submitTodosPedidos();">Todos</button>


                        <strong>
                            {if $mensagem neq ''}
                                {if $tipoMsg eq 'sucesso'}
                                    <div class="row">
                                        <div class="col-lg-12 text-left">
                                            <div>
                                                <div class="alert alert-success" role="alert">
                                                    <strong>--Sucesso!</strong>&nbsp;{$mensagem}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                {elseif $tipoMsg eq 'alerta'}
                                    <div class="row">
                                        <div class="col-lg-12 text-left">
                                            <div>
                                                <div class="alert alert-danger" role="alert">
                                                    <strong>--Aviso!</strong>&nbsp;{$mensagem}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                {/if}
                            {/if}
                        </strong>

                        <ul class="nav navbar-right panel_toolbox">
                            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                                    aria-expanded="false"><i class="fa fa-wrench"></i></a>
                                <ul class="dropdown-menu" role="menu">
                                    <li><button type="button" class="btn btn-primary btn-xs"
                                            onClick="javascript:agrupaPedidoModal();">
                                            <!-- onClick="javascript:submitAgruparPedidos();"  data-toggle="modal"  data-target="#modalAgrupamentoPed"-->
                                            <span class="glyphicon glyphicon-plus" aria-hidden="true"></span><span>
                                                Agrupar Pedidos</span>
                                        </button>
                                    </li>
                                </ul>
                            </li>
                            <li><a class="close-link"><i class="fa fa-close"></i></a>
                            </li>
                        </ul>
                    </h2>


                    <div class="x_content">
                        <form id="lancamento" name="lancamento" data-parsley-validate METHOD="POST"
                            class="form-horizontal form-label-left" ACTION={$SCRIPT_NAME}>
                            <input name=mod type=hidden value="{$mod}">
                            <input name=form type=hidden value="{$form}">
                            <input name=origem type=hidden value="{$origem}">
                            <input name=opcao type=hidden value="">
                            <input name=id type=hidden value="">
                            <input name=letra type=hidden value={$letra}>
                            <input name=submenu type=hidden value={$subMenu}>
                            <input name=pedidoAgrupado type=hidden value={$pedidoAgrupado}>
                            <input name=pessoa type=hidden value={$pessoa}>
                            <input name=dadosPed type=hidden value={$dadosPed}>

                            <!-- INCLUDES DE MODAL -->

                        </form>
                        <table id="datatable-buttons" class="table table-bordered jambo_table">
                            <thead>
                                <tr class="headings">
                                    <th id="checkBox"></th>
                                    <th>Pessoa</th>
                                    <th>Pedido</th>
                                    <th>Emiss&atilde;o</th>
                                    <th>Total</th>
                                    <th class='invis'></th>
                                    <th style="width:60px;">
                                        <center>Emitir NFe</center>
                                    </th>
                                    <th style="width:110px;">
                                        <center>Financeiro</center>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                {section name=i loop=$lanc}
                                    {if $lanc[i].SITUACAO eq 6}
                                        {assign var="total" value=$total+1}
                                        <tr>
                                            <td>
                                                <center> <input type="checkBox" name="pedidoChecked" id="{$lanc[i].PEDIDO}" />
                                                </center>
                                            </td>
                                            <td> {$lanc[i].NOME} </td>
                                            <td> Ped:{$lanc[i].PEDIDO} </td>
                                            <td> {$lanc[i].EMISSAO|date_format:"%d/%m/%Y"} </td>
                                            <td> {$lanc[i].TOTAL|number_format:2:",":"."} </td>
                                            <td class='invis'> {$lanc[i].FRETE|number_format:2:",":"."} |
                                                {$lanc[i].DESPACESSORIAS|number_format:2:",":"."} |
                                                {$lanc[i].DESCONTO|number_format:2:",":"."} | {$lanc[i].CLIENTE} |
                                                {$lanc[i].CONDPG}</td>
                                            <td>
                                                <center>
                                                    <button id="btnEmissaoNf" type="button" class="btn btn-success btn-xs"
                                                        onclick="javascript:submitImprime('{$lanc[i].ID}', 'index.php?mod=ped&form=pedido_venda_imp_romaneio&opcao=imprimir&parm={$lanc[i].ID}');">
                                                        <center><span class="glyphicon glyphicon-print"
                                                                aria-hidden="true"></span></center>
                                                    </button>
                                                </center>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-info btn-xs dropdown-toggle"
                                                    onclick="javascript:submitCadastroFinanceiro('{$lanc[i].ID}');">Produtos</button>
                                                <button id="btnFinServico"
                                                    {if $lanc[i].VALORSERVICOS|number_format:2:",":"." eq "0,00" } disabled
                                                    {/if}type="button" class="btn btn-info btn-xs dropdown-toggle"
                                                    onclick="javascript:submitCadastroFinanceiroServico('{$lanc[i].ID}');">Serviços</button>
                                            </td>

                                        </tr>
                                    {/if}
                                {/section}
                            </tbody>
                        </table>

                    </div>
                </div> <!-- div class="x_content" = inicio tabela -->

            </div> <!-- div class="x_panel" = painel principal-->

            <div class="col-md-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Emitir Nota Fiscal
                        </h2>
                        <!--button type="button" class="btn btn-info btn-xs dropdown-toggle" onclick="javascript:submitMesAtual();">Mês Atual</button-->



                        <div class="clearfix"></div>
                        <div class="x_content">

                            <table id="datatable-buttons-1" class="table table-bordered jambo_table">

                                <thead>
                                    <tr class="headings">
                                        <th>Pessoa</th>
                                        <th style="width: 85px;">
                                            <center>Pedido</center>
                                        </th>
                                        <th style="width: 80px;">
                                            <center>Emiss&atilde;o</center>
                                        </th>
                                        <th>Total</th>
                                        <th style="width: 80px;">
                                            <center>Emitir</center>
                                        </th>

                                    </tr>
                                </thead>
                                <tbody>
                                    {section name=i loop=$lanc}
                                        {if $lanc[i].SITUACAO eq 3}
                                            {assign var="total" value=$total+1}
                                            <tr>
                                                <td> {$lanc[i].NOME} </td>
                                                <td> Ped:{$lanc[i].PEDIDO} </td>
                                                <td> {$lanc[i].EMISSAO|date_format:"%d/%m/%Y"} </td>
                                                <td> {$lanc[i].TOTAL|number_format:2:",":"."} </td>

                                                <td>
                                                    <button type="button" class="btn btn-success btn-xs dropdown-toggle"
                                                        onclick="javascript:submitCadastro('{$lanc[i].ID}');">Nota
                                                        Fiscal</button>
                                                </td>
                                            </tr>
                                        {/if}
                                    {/section}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>




            <!-- div class="col-md-12 col-sm-12 col-xs-12 "-->
        </div> <!-- div class="row "-->
    </div> <!-- class='' = controla menu user -->

    <!-- /Datatables -->


    {include file="template/database.inc"}
