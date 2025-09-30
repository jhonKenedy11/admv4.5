<style>
    .form-control,
    .c_panel {
        border-radius: 5px;
    }

    #msgRetorno {
        font-size: 12px !important;
    }
</style>

<script type="text/javascript" src="{$pathJs}/est/s_nota_fiscal_servico.js"></script>
<script type="text/javascript" src="{$pathSweet}/dist/sweetalert2.all.min.js"></script>

<!-- page content -->
<div class="right_col" role="main">
    <div class="">
        <div class="row">
            <!-- panel principal  -->
            <div class="col-md-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Nota Fiscal de Serviço - Consulta
                            <strong>
                                {if $mensagem neq ''}
                                    {if $tipoMsg eq 'sucesso'}
                                        <div class="alert alert-success" role="alert" id="msgRetorno">{$mensagem}</div>
                                    {else}
                                        <div class="alert alert-danger" role="alert" id="msgRetorno">{$mensagem}</div>
                                    {/if}
                                {/if}
                            </strong>
                        </h2>

                        <ul class="nav navbar-right panel_toolbox">
                            <li>
                                <button type="button" class="btn btn-warning" onClick="javascript:submitPesquisa();">
                                    <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                                    <span>Pesquisa</span>
                                </button>
                            </li>
                            <li>
                                <button type="button" class="btn btn-primary" onClick="javascript:submitCadastro();">
                                    <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                                    <span>Cadastro</span>
                                </button>
                            </li>
                           
                        </ul>
                        <div class="clearfix"></div>
                    </div>

                    <div class="x_content">
                        <form id="lancamento" name="lancamento" METHOD="POST" class="form-horizontal form-label-left" ACTION={$SCRIPT_NAME}>
                            <input name="mod" type="hidden" value="est">
                            <input name="form" type="hidden" value="nota_fiscal_servico">
                            <input name="id" type="hidden" value="">
                            <input name="opcao" type="hidden" value="">
                            <input name="submenu" type="hidden" value="{$submenu}">
                            <input name="pessoa" type="hidden" value="">
                            <input name="nome" type="hidden" value="">
                            <input name="dataIni" type="hidden" value="{$dataIni}">
                            <input name="dataFim" type="hidden" value="{$dataFim}">
                            
                            <div class="row">
                                <div class="col-md-2">
                                    <label for="numNfs">Número NFS:</label>
                                    <input type="text" class="form-control" id="numNfs" name="numNfs" placeholder="Número" maxlength="10">
                                </div>
                                <div class="col-md-2">
                                    <label for="serieNfs">Série:</label>
                                    <input type="text" class="form-control" id="serieNfs" name="serieNfs" placeholder="Série" maxlength="5">
                                </div>
                                <div class="col-md-3">
                                    <label for="status">Status:</label>
                                    <select class="form-control" id="status" name="status">
                                       
                                    </select>
                                </div>
                                <div class="col-md-5">
                                    <label for="dataConsulta">Período:</label>
                                    <input type="text" class="form-control" id="dataConsulta" name="dataConsulta" placeholder="Selecione o período">
                                    <input type="hidden" id="dataIni" name="dataIni" value="">
                                    <input type="hidden" id="dataFim" name="dataFim" value="">
                                </div>
                            </div>
                            <div class="row" style="margin-top: 15px;">
                                <div class="col-md-6">
                                    <label for="cliente">Cliente:</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="cliente_nome" name="cliente" 
                                               placeholder="Nome ou CPF/CNPJ do cliente" value="">
                                        <input type="hidden" id="cliente_id" name="cliente_id" value="">
                                        <span class="input-group-btn">
                                            <button type="button" class="btn btn-primary" 
                                                    onClick="javascript:abrir('{$pathCliente}/index.php?mod=crm&form=contas&opcao=pesquisarRelatorios');">
                                                <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                                            </button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- panel tabela dados -->
        <div class="col-md-12 col-xs-12">
            <div class="x_panel small">
                <table id="datatable-buttons" class="table table-bordered jambo_table">
                    <thead>
                        <tr style="background: #2A3F54; color: white;">
                            <th>
                                <input type="checkbox" id="checkTodos" onClick="javascript:selecionarTodas();" />
                            </th>
                            <th>#</th>
                            <th>ID</th>
                            <th>Emissão</th>
                            <th>Número</th>
                            <th>Série</th>
                            <th>Cliente</th>
                            <th>CPF/CNPJ</th>
                            <th>Status</th>
                            <th>Total</th>
                            <th style="width: 120px;">Manutenção</th>
                        </tr>
                    </thead>
                    <tbody>
                        {if $notasFiscais}
                            {section name=i loop=$notasFiscais}
                                <tr>
                                    <td>
                                        <input type="checkbox" name="nfChecked" class="nfChecked" value="{$notasFiscais[i].ID}" />
                                    </td>
                                    <td name="idNFS" id="{$notasFiscais[i].ID}"> {$notasFiscais[i].ID} </td>
                                    <td> {$notasFiscais[i].DATA_EMISSAO|date_format:"%e %b, %Y"} </td>
                                    <td> {$notasFiscais[i].NUMERO} </td>
                                    <td> {$notasFiscais[i].SERIE} </td>
                                    <td> {$notasFiscais[i].NOME_CLIENTE} </td>
                                    <td> {$notasFiscais[i].CPF_CNPJ} </td>
                                    <td> 
                                        <span class="label label-{if $notasFiscais[i].STATUS eq 'PENDENTE'}warning{elseif $notasFiscais[i].STATUS eq 'EMITIDA'}success{elseif $notasFiscais[i].STATUS eq 'CANCELADA'}danger{elseif $notasFiscais[i].STATUS eq 'DEVOLVIDA'}default{else}info{/if}">
                                            {$notasFiscais[i].STATUS}
                                        </span>
                                    </td>
                                    <td> R$ {$notasFiscais[i].VALOR_TOTAL|number_format:2:',':'.'} </td>
                                    <td>
                                        <button type="button" title="Visualizar" class="btn btn-info btn-xs" onClick="javascript:visualizarNota('{$notasFiscais[i].ID}');">
                                            <span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>
                                        </button>
                                        <button type="button" title="Alterar" class="btn btn-primary btn-xs" onClick="javascript:alterarNota('{$notasFiscais[i].ID}');">
                                            <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                                        </button>
                                        <button type="button" title="Imprimir" class="btn btn-success btn-xs" onClick="javascript:imprimirNota('{$notasFiscais[i].ID}');">
                                            <span class="glyphicon glyphicon-print" aria-hidden="true"></span>
                                        </button>
                                        <button type="button" title="Deletar" class="btn btn-danger btn-xs" onClick="javascript:deletarNota('{$notasFiscais[i].ID}');">
                                            <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                                        </button>
                                    </td>
                                </tr>
                            {/section}
                        {/if}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{include file="template/database.inc"}  

    <!-- bootstrap-daterangepicker -->
<script src="js/moment/moment.min.js"></script>
<script src="js/datepicker/daterangepicker.js"></script>

<!-- daterangepicker -->
<script type="text/javascript">
    $('input[name="dataConsulta"]').daterangepicker({
            startDate: moment("{$dataIni}", "DD/MM/YYYY"),
            endDate: moment("{$dataFim}", "DD/MM/YYYY"),
            ranges: {
                'Hoje': [moment(), moment()],
                'Ontem': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Últimos 7 Dias': [moment().subtract(6, 'days'), moment()],
                'Últimos 30 Dias': [moment().subtract(29, 'days'), moment()],
                'Este Mes': [moment().startOf('month'), moment().endOf('month')],
                'Último Mes': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            },
            locale: {
                format: 'DD/MM/YYYY',
                applyLabel: 'Confirma',
                cancelLabel: 'Limpa',
                fromLabel: 'Início',
                toLabel: 'Fim',
                customRangeLabel: 'Calendário',
                daysOfWeek: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
                monthNames: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto',
                    'Setembro', 'Outubro', 'Novembro', 'Dezembro'
                ],
                firstDay: 1
            }
        },
        //funcao para recuperar o valor digitado        
        function(start, end, label) {
            var f = document.lancamento;
            if (f.dataIni && f.dataFim) {
                f.dataIni.value = start.format('DD/MM/YYYY');
                f.dataFim.value = end.format('DD/MM/YYYY');
            }
        });
</script> 

<!-- Include do Modal de Serviços -->
{include file="modal_servicos.tpl"} 