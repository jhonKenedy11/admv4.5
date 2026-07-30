<style>
    .x_panel,
    .form-control {
        padding: 8px;
        border-radius: 5px;
    }

    .right_col {
        padding-left: 5px !important;
        padding-right: 5px !important;
        padding-top: 0 !important;
    }

    /* Para navegadores Webkit (Chrome, Safari, Edge) */
    input[type=number]::-webkit-inner-spin-button,
    input[type=number]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    /* Para Firefox */
    input[type=number] {
        -moz-appearance: textfield;
    }

    /* Para Microsoft Edge */
    input[type=number]::-ms-clear,
    input[type=number]::-ms-reveal {
        display: none;
        width: 0;
        height: 0;
    }
    .spanMeta{
        padding-left: 5px;
        padding-right: 5px;
    }
    .fontForm{
        font-size: 13px;
    }
</style>
<script type="text/javascript" src="{$pathJs}/est/s_meta_mensal.js"> </script>
<script type="text/javascript" src="{$pathSweet}/dist/sweetalert2.all.min.js"></script>    

<div class="right_col" role="main">
    <div class="">
        <div class="clearfix"></div>

        <form id="lancamento" data-parsley-validate class="form-horizontal form-label-left" NAME="lancamento"
            ACTION="{$SCRIPT_NAME}" METHOD="post">
            <input name=mod type=hidden value="{$mod}">
            <input name=form type=hidden value="{$form}">
            <input name=opcao type=hidden value="">
            <input name=submenu type=hidden value={$subMenu}>
            <input name=letra type=hidden value={$letra}>
            <input name=metaid type=hidden value={$letra}>
            <input name=id type=hidden value={$id}>


            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2><b>Metas empresa </b> -
                                {if $subMenu eq "cadastrar"}
                                    cadastro
                                {else}
                                    altera&ccedil;&atilde;o
                                {/if}
                                {if $mensagem neq ''}
                                    {if $tipoMsg eq 'sucesso'}
                                        <div class="row">
                                            <div class="col-lg-12 text-left">
                                                <div>
                                                    <div class="alert alert-success" role="alert"><strong>{$mensagem}</div>
                                                </div>
                                            </div>
                                        </div>
                                    {elseif $tipoMsg eq 'alerta'}
                                        <div class="row">
                                            <div class="col-lg-12 text-left">
                                                <div>
                                                    <div class="alert alert-danger" role="alert"><strong>{$mensagem}</div>
                                                </div>
                                            </div>
                                        </div>
                                    {/if}

                                {/if}
                            </h2>

                            <ul class="nav navbar-right panel_toolbox">
                                <li><button type="button" class="btn btn-success"
                                        onClick="javascript:submitConfirmar('');">
                                        <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span><span>
                                            Confirmar</span></button>
                                </li>
                                {if $subMenu != "cadastrar"}
                                    <li><button type="button" class="btn btn-primary"
                                            onClick="javascript:submitAddMetaUsuario('');">
                                            <span class="glyphicon glyphicon-plus" aria-hidden="true"></span><span>
                                                Adicionar Meta Usuário</span></button>
                                    </li>
                                {/if}
                                <li><button type="button" class="btn btn-danger" onClick="javascript:submitVoltar('');">
                                        <span class="glyphicon glyphicon-backward" aria-hidden="true"></span><span>
                                            Voltar</span></button>
                                </li>
                                {* <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                      </li>
                      <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                      </li>
                      <li><a class="close-link"><i class="fa fa-close"></i></a>
                      </li> *}
                            </ul>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <br />
                            <div class="row">
                                <div class="col-md-3 col-sm-6 col-xs-6">
                                    <label for="centrocusto">Centro Custo</label>
                                    <div class="input-group" style="border-radius: 5px !important;">
                                        <SELECT class="form-control form-control-sm fontForm" name="centrocusto"
                                            required="required">
                                            {html_options values=$centroCusto_ids selected=$centroCusto_id output=$centroCusto_names}
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-2 col-sm-6 col-xs-6">
                                    <label for="metamargem">Meta Margem</label>
                                    <div class="input-group">
                                        <input class="form-control input money fontForm" maxlength="14" type="text" id="metamargem"
                                            name="metamargem" value={$metamargem}>
                                        <span class="input-group-btn">
                                            <button class="btn btn-default" type="button">%</button>
                                        </span>
                                    </div>
                                </div>

                                <div class="col-md-2 col-sm-6 col-xs-6">
                                    <label for="meta">Meta</label>
                                    <div class="input-group">
                                        <span class="input-group-btn">
                                            <button class="btn btn-default spanMeta" type="button">R$</button>
                                        </span>
                                        <input class="form-control input money fontForm" maxlength="14" type="text" id="meta" name="meta"
                                            value={$meta}>
                                    </div>
                                </div>

                                <div class="col-md-1 col-sm-3 col-xs-3">
                                    <label for="ano">Ano <span class="required"></span></label>
                                    <div>
                                        <input class="form-control fontForm" maxlength="4" type="text" id="ano" name="ano" value={$ano}>
                                    </div>
                                </div>

                                <div class="col-md-2 col-sm-2 col-xs-2">
                                    <label for="mes">Mês</label>
                                    <div>
                                        <SELECT class="form-control fontForm" name="mes" required="required">
                                            {html_options values=$mes_ids selected=$mes_id output=$mes_names}
                                        </SELECT>
                                    </div>
                                </div>

                                <div class="col-md-2 col-sm-2 col-xs-2">
                                    <label for="totaldiames">Total de dia no Mês <span class="required"></span></label>
                                    <div>
                                        <input class="form-control fontForm" maxlength="11" type="number" id="totaldiames" name="totaldiames"
                                            value={$totaldiames}>
                                    </div>
                                </div>
                            </div>

                            <!--
                      <div class="form-group">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="ano">Ano <span class="required"></span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input class="form-control" type="text" id="ano" name="ano" value={$ano}>    
                        </div>
                      </div>

                      <div class="form-group">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="mes">Mês <span class="required"></span>
                          </label>
                          <div class="col-md-6 col-sm-6 col-xs-12">
                          <select class="form-control" name=mes id="mes">
                                {html_options values=$mes_ids selected=$mes_id output=$mes_names}
                          </select>
                          </div>
                      </div>

                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="meta">Meta Margem<span class="required"></span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input class="form-control" type="text" id="metamargem" name="metamargem" value={$metamargem}>
                        </div>
                      </div>

                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="meta">Total Dia Mês<span class="required"></span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input class="form-control" type="text" id="totaldiames" name="totaldiames" value={$totaldiames}>
                        </div>
                      </div>

                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="meta">Centro de Custo<span class="required"></span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <div class="panel panel-default small">
                                <select name="centrocusto" class="form-control">
                                    {html_options values=$centroCusto_ids selected=$centroCusto_id output=$centroCusto_names}
                                </select>
                          </div>
                          </div>
                      </div> -->




                            <!--table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap table-condensed" cellspacing="0" width="100%"-->
                            <table id="datatable-buttons" class="table table-bordered jambo_table">
                                <thead>
                                    <tr class="headings">
                                        <th>Vendedor</th>
                                        <th>Meta</th>
                                        <th class=" no-link last" style="width: 60px;">Manutenção</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    {section name=i loop=$metas}
                                        <tr class="even pointer">
                                            <td> {$metas[i].VENDEDOR} - {$metas[i].NOME}</td>
                                            <td> R$ {$metas[i].META|number_format:2:",":"."} </td>
                                            <td class=""><center>
                                                <button type="button" class="btn btn-primary btn-xs"
                                                    onclick="javascript:submitAlterarVendedor('{$metas[i].ID}','{$metas[i].METAID}');"><span
                                                        class="glyphicon glyphicon-pencil"
                                                        aria-hidden="true"></span></button>
                                                <button type="button" class="btn btn-danger btn-xs"
                                                    onclick="javascript:submitExcluirVendedor('{$metas[i].ID}','{$metas[i].METAID}');"><span
                                                        class="glyphicon glyphicon-trash"
                                                        aria-hidden="true"></span></button>
                                            </center></td>
                                        </tr>
                                    {/section}

                                </tbody>

                            </table>
                        </div>

                        <div class="ln_solid"></div>

                    </div>
                </div>
            </div>
    </div>
    </form>

</div>
{include file="template/form.inc"}

<script src="https://cdn.rawgit.com/plentz/jquery-maskmoney/master/dist/jquery.maskMoney.min.js"></script>
<script>
    $(document).ready(function() {
        $(".money").maskMoney({
            decimal: ",",
            thousands: ".",
            allowZero: true,
        });
    });
</script>