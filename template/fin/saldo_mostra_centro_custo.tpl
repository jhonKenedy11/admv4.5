<style>
.form-control,
.x_panel {
    border-radius: 5px;
}
</style>
<script type="text/javascript" src="{$pathJs}/fin/s_saldo_centro_custo.js"> </script>
<!-- page content -->
<div class="right_col" role="main">
    <form class="full" NAME="lancamento" METHOD="POST" class="form-horizontal form-label-left" novalidate
        ACTION={$SCRIPT_NAME}>
        <input name=mod type=hidden value="fin">
        <input name=form type=hidden value="saldo_centro_custo">
        <input name=id type=hidden value="">
        <input name=letra type=hidden value={$letra}>
        <input name=submenu type=hidden value={$subMenu}>


        <div class="">


            <div class="row">

                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>Saldo Banc&aacute;rio Centro Custo - Consulta
                                <strong>
                                    {if $mensagem neq ''}
                                        <div class="alert alert-danger" role="alert">{$mensagem}</div>
                                    {/if}
                                </strong>
                            </h2>
                            <ul class="nav navbar-right panel_toolbox">
                                <li><button type="button" class="btn btn-warning"
                                        onClick="javascript:submitPesquisar();">
                                        <span class="glyphicon glyphicon-zoom-in" aria-hidden="true"></span><span>
                                            Pesquisa</span>
                                    </button>
                                </li>
                                <li><button type="button" class="btn btn-primary"
                                        onClick="javascript:submitCadastro();">
                                        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span><span>
                                            Cadastro</span>
                                    </button>
                                </li>
                                {* <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                                        aria-expanded="false"><i class="fa fa-wrench"></i></a>
                                </li>
                                <li><a class="close-link"><i class="fa fa-close"></i></a>
                                </li> *}
                            </ul>
                            <div class="clearfix"></div>
                        </div>


                        <div class="x_content">
                            <div class="form-group col-md-2 col-sm-12 col-xs-12">
                                <label for="mesSaldo">M&ecirc;s Saldo</label>
                                <select class="form-control" name=mesSaldo id="mesSaldo">
                                    {html_options values=$mesSaldo_ids selected=$mesSaldo_id output=$mesSaldo_names}
                                </select>
                            </div>
                            <div class="form-group col-md-2 col-sm-12 col-xs-12">
                                <label for="anoSaldo">Ano Saldo</label>
                                <input class="form-control" type="text" id="anoSaldo" name="anoSaldo"
                                    value="{$anoSaldo}">
                            </div>
                            <div class="form-group col-md-3 col-sm-6 col-xs-6">
                                <label for="contaPes">Conta</label>
                                <select class="form-control" name="contaPes" id="contaPes">
                                    {html_options values=$contaPesq_ids selected=$contaPesq_id output=$contaPesq_names}
                                </select>
                            </div>
                        </div>

                        <div class="x_content">
                            <!--table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap table-condensed" cellspacing="0" width="100%"-->
                            <table id="datatable-buttons" class="table table-bordered jambo_table">
                                <thead>
                                    <tr class="headings">
                                        <th>Conta</th>
                                        <th>Data</th>
                                        <th>Saldo</th>
                                        <th class=" no-link last" style="width: 40px;">Manuten&ccedil;&atilde;o</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    {section name=i loop=$lanc}
                                        {assign var="total" value=$total+1}
                                        <tr class="even pointer">
                                            <td> {$lanc[i].DESCRICAO} </td>
                                            <td> {$lanc[i].DATA|date_format:"%d/%m/%Y"} </td>
                                            <td> {$lanc[i].SALDO|number_format:2:",":"."} </td>
                                            <td class=" last">
                                                <button type="button" class="btn btn-primary btn-xs"
                                                    onclick="javascript:submitAlterar('{$lanc[i].ID}');"><span
                                                        class="glyphicon glyphicon-pencil"
                                                        aria-hidden="true"></span></button>
                                                <button type="button" class="btn btn-danger btn-xs"
                                                    onclick="javascript:submitExcluir('{$lanc[i].ID}');"><span
                                                        class="glyphicon glyphicon-trash"
                                                        aria-hidden="true"></span></button>
                                            </td>
                                        </tr>
                                    {/section}

                                </tbody>

                            </table>

                        </div> <!-- div class="x_content" = inicio tabela -->
                    </div> <!-- div class="x_panel" = painel principal-->
                </div> <!-- div class="col-md-12 col-sm-12 col-xs-12 "-->
            </div> <!-- div class="row "-->
        </div> <!-- class='' = controla menu user -->

    </form>
</div>


{include file="template/database.inc"}

<!-- /Datatables -->