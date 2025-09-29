<style>
    .form-control,
    .x_panel {
        border-radius: 5px;
    }
</style>
<script type="text/javascript" src="{$pathSweet}/dist/sweetalert2.all.min.js"></script>
<script type="text/javascript" src="{$pathJs}/fin/s_orcamento.js"></script>

<!-- page content -->
<div class="right_col" role="main">
    <form id="lancamento" name="lancamento" data-parsley-validate METHOD="POST" class="form-horizontal form-label-left"
        ACTION={$SCRIPT_NAME}>
        <input name="mod" type="hidden" value="fin">
        <input name="form" type="hidden" value="orcamento">
        <input name="id" type="hidden" value="">
        <input name="letra" type="hidden" value="{$letra}">
        <input name="submenu" type="hidden" value="{$subMenu}">
        <input name="opcao" type="hidden" value="">
        <input name="fornecedor" type="hidden" value="">
        <input name="pessoa" type="hidden" value="{$pessoa}">
        <input name="genero" type="hidden" value="{$genero}">
        <input name="dataIni" type="hidden" value="{$dataIni}">
        <input name="dataFim" type="hidden" value="{$dataFim}">
        <input name="linhas" type="hidden" value="{$linhas}">
        <input name="vencimento" type="hidden" value="{$vencimento}">
        <input name="total" type="hidden" value="{$total}">
        <input name="mes" type="hidden" value="{$mes}">
        <input name="ano" type="hidden" value="{$ano}">
        

        <div class="">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>Previsão - Consulta</h2>
                            {include file="../bib/msg.tpl"}
                            <ul class="nav navbar-right panel_toolbox">
                                <li> <button type="submit" class="btn btn-warning" onclick="submitPesquisar('');">
                                        <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                                        <span> Pesquisar</span>
                                    </button>
                                </li>
                                <li>
                                    <button type="button" class="btn btn-primary" onclick="submitCadastro('');">
                                        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                                        <span> Cadastro</span>
                                    </button>
                                </li>
                                <li>
                                    <button type="button" class="btn btn-info" onclick="submitParOrcamento('');">
                                        <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span>
                                        <span> Gerar Previsão</span>
                                    </button>
                                </li>
                            </ul>
                            <div class="clearfix"></div>
                        </div>

                        <div class="x_content">
                            <div class="row">
                                <div class="form-group col-md-3 col-sm-6 col-xs-12">
                                    <label for="mesBase">Mês Base</label>
                                    <select class="form-control" name="mesBase" id="mesBase">
                                        {html_options values=$mesBase_ids selected=$mesBase_id output=$mesBase_names}
                                    </select>
                                </div>

                                <div class="form-group col-md-2 col-sm-6 col-xs-12">
                                    <label for="anoBase">Ano Base</label>
                                    <input type="text" class="form-control" id="anoBase" name="anoBase"
                                        value="{$anoBase}">
                                </div>

                                <div class="form-group col-md-3 col-sm-6 col-xs-12">
                                    <label for="filial">Conta</label>
                                    <select class="form-control" name="filial" id="filial">
                                        {html_options values=$filial_ids selected=$filial_id output=$filial_names}
                                    </select>
                                </div>
                            </div>

                            <table id="datatable-buttons" class="table table-bordered jambo_table">
                                <thead>
                                    <tr class="headings">
                                        <th class="text-center">Mês/Ano</th>
                                        <th>Gênero</th>
                                        <th>Descrição</th>
                                        <th>Filial</th>
                                        <th>Valor</th>
                                        <th>Total</th>
                                        <th class="no-link last" style="width: 80px;">Manutenção</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    {section name=i loop=$lanc}
                                        {assign var="total" value=$total+$lanc[i].TOTAL}
                                        <tr class="even pointer">
                                            <td class="text-center">{$lanc[i].MES}/{$lanc[i].ANO}</td>
                                            <td>{$lanc[i].GENERO}</td>
                                            <td>{$lanc[i].DESCRICAO}</td>
                                            <td>{$lanc[i].FILIAL}</td>
                                            <td>{$lanc[i].VALOR|number_format:2:",":"."}</td>
                                            <td>{$lanc[i].TOTAL|number_format:2:",":"."}</td>
                                            <td class="last">
                                                <button type="button" class="btn btn-primary btn-xs"
                                                    onclick="submitAlterar({$lanc[i].MES},{$lanc[i].ANO},{$lanc[i].CENTROCUSTO},'{$lanc[i].GENERO}');">
                                                    <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                                                </button>
                                                <button type="button" class="btn btn-danger btn-xs"
                                                    onclick="submitExcluir({$lanc[i].MES},{$lanc[i].ANO},{$lanc[i].CENTROCUSTO},'{$lanc[i].GENERO}');">
                                                    <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                                                </button>
                                            </td>
                                        </tr>
                                    {/section}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    {include file="template/database.inc"}
</div>