<style>
    .form-control,
    .x_panel {
        border-radius: 5px;
    }
</style>
<script type="text/javascript" src="{$pathJs}/fin/s_extrato.js"></script>
{if $subMenu neq "cadastrar"}

    <body onload="tipoLancamento()">
    {/if}

    <!-- page content -->
    <div class="right_col" role="main">
        <div class="">
            <div class="clearfix"></div>
            <form id="lancamento" data-parsley-validate class="form-horizontal form-label-left" name="lancamento"
                action="{$SCRIPT_NAME}" method="post">
                <input name="mod" type="hidden" value="">
                <input name="form" type="hidden" value="">
                <input name="submenu" type="hidden" value="{$subMenu}">
                <input name="letra" type="hidden" value="{$letra}">
                <input name="opcao" type="hidden" value="">
                <input name="id" type="hidden" value="{$id}">
                <input name="fornecedor" type="hidden" value="{$fornecedor}">
                <input name="pessoa" type="hidden" value="{$pessoa}">
                <input name="genero" type="hidden" value="{$genero}">
                <input name="centroCusto" type="hidden" value="{$centroCusto}">
                <input name="tipolancamento" type="hidden" value="{$tipolancamento}">

                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2>Extrato Financeiro -
                                    {if $subMenu eq "cadastrar"}
                                        Cadastro
                                    {else}
                                        Alteração
                                    {/if}
                                </h2>
                                {include file="../bib/msg.tpl"}
                                <ul class="nav navbar-right panel_toolbox">
                                    <li>
                                        <button type="button" class="btn btn-primary"
                                            onclick="javascript:submitConfirmar('');">
                                            <span class="glyphicon glyphicon-floppy-save" aria-hidden="true"></span>
                                            <span> Confirmar</span>
                                        </button>
                                    </li>
                                    <li>
                                        <button type="button" class="btn btn-danger"
                                            onclick="javascript:submitVoltar('');">
                                            <span class="glyphicon glyphicon-backward" aria-hidden="true"></span>
                                            <span> Voltar</span>
                                        </button>
                                    </li>
                                </ul>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                                <br />

                                <div class="form-group">
                                    <div class="col-md-6 col-sm-12 col-xs-12">
                                        <label for="nome">Pessoa</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="nome" name="nome"
                                                placeholder="Nome" required value="{$pessoaNome}">
                                            <span class="input-group-btn">
                                                <button type="button" class="btn btn-primary"
                                                    onclick="javascript:abrir('{$pathCliente}/index.php?mod=crm&form=contas&opcao=pesquisar');">
                                                    <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                                                </button>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="col-md-4 col-sm-12 col-xs-12">
                                        <label for="genero">Gênero</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="descgenero" name="descgenero"
                                                placeholder="Genero" required value="{$descGenero}">
                                            <span class="input-group-btn">
                                                <button type="button" class="btn btn-primary"
                                                    onclick="javascript:abrir('{$pathCliente}/index.php?mod=fin&form=genero&opcao=pesquisar');">
                                                    <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                                                </button>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="col-md-2 col-sm-12 col-xs-12">
                                        <div id="divDescTipo" class="" align="center">
                                            <b><label id="descTipo"></label></b>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-md-3 col-sm-4 col-xs-4">
                                        <label for="situacaoLancamento">Situação</label>
                                        <select class="form-control" name="situacaoLancamento" id="situacaoLancamento">
                                            {html_options values=$situacaoLanc_ids selected=$situacaoLanc_id output=$situacaoLanc_names}
                                        </select>
                                    </div>

                                    <div class="col-md-3 col-sm-12 col-xs-12 has-feedback">
                                        <label for="dataLancamento">Data Lançamento:</label>
                                        <input class="form-control has-feedback-left" type="text" id="dataLancamento"
                                            name="dataLancamento" required value="{$dataLancamento}">
                                        <span class="fa fa-calendar-o form-control-feedback left"
                                            aria-hidden="true"></span>
                                    </div>

                                    <div class="col-md-3 col-sm-12 col-xs-12 has-feedback">
                                        <label for="dataCompetencia">Data Competência:</label>
                                        <input class="form-control has-feedback-left" type="text" id="dataCompetencia"
                                            name="dataCompetencia" required value="{$dataCompetencia}">
                                        <span class="fa fa-calendar-o form-control-feedback left"
                                            aria-hidden="true"></span>
                                    </div>

                                    <div class="col-md-3 col-sm-12 col-xs-12 has-feedback">
                                        <label for="valor">Valor Lançamento:</label>
                                        <input class="form-control has-feedback-left money" type="text" id="valor"
                                            name="valor" required
                                            value="{if $valor != '' && is_numeric($valor)}{$valor|number_format:2:",":"."}{/if}">
                                        <span class="form-control-feedback left" aria-hidden="true"><b>R$</b></span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-md-6 col-sm-12 col-xs-12">
                                        <label for="nome">Fornecedor</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="fornecedorNome"
                                                name="fornecedorNome" placeholder="Nome Fornecedor"
                                                value="{$fornecedorNome}">
                                            <span class="input-group-btn">
                                                <button type="button" class="btn btn-primary"
                                                    onclick="javascript:abrir('{$pathCliente}/index.php?mod=crm&form=contas&opcao=pesquisarfornecedor');">
                                                    <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                                                </button>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-sm-6 col-xs-6">
                                        <label for="obs">Observação</label>
                                        <textarea class="resizable_textarea form-control" id="obs" name="obs"
                                            rows="3">{$obs}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        {include file="template/form.inc"}

        <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap-daterangepicker@3.1.0/daterangepicker.min.js"></script>
        <script src="https://cdn.rawgit.com/plentz/jquery-maskmoney/master/dist/jquery.maskMoney.min.js"></script>

        <script>
            $(function() {
                // Configuração dos datepickers
                $('#dataLancamento, #dataCompetencia').daterangepicker({
                    singleDatePicker: true,
                    locale: {
                        format: 'DD/MM/YYYY',
                        daysOfWeek: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
                        monthNames: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho',
                            'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'
                        ],
                        applyLabel: 'Aplicar',
                        cancelLabel: 'Cancelar',
                        fromLabel: 'De',
                        toLabel: 'Até',
                        customRangeLabel: 'Personalizado',
                        firstDay: 0
                    },
                    opens: 'right',
                    drops: 'down'
                });

                // Configuração da máscara monetária
                $(".money").maskMoney({
                    decimal: ",",
                    thousands: ".",
                    allowNegative: true,
                    allowZero: true,
                    prefix: "R$ ",
                    affixesStay: false
                });

                // Dispara a função tipoLancamento se necessário
                {if $subMenu neq "cadastrar"}
                    tipoLancamento();
                {/if}
            });
        </script>
</div>