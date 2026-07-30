<style>
    /* Tema de LOG - Tons de cinza e azul escuro */
    .x_panel {
        border-radius: 5px;
        border-left: 4px solid rgb(121, 125, 80);
        background: #f8f9fa;
    }
    
    .form-control {
        border-radius: 5px;
    }

    .right_col {
        padding: 1px !important;
        background: #e9ecef;
    }

    /* Título com identidade de LOG */
    .x_title {
        background: linear-gradient(135deg, rgb(121, 125, 80) 20%, #2d3748 100%);
        color: white !important;
        padding: 15px;
        border-radius: 5px 5px 0 0;
        margin: -1px -1px 15px -1px;
    }
    
    .x_title h2 {
        color: white !important;
        font-weight: 600;
    }
    
    .x_title h2 small {
        color: #cbd5e0 !important;
    }

    #msgRetorno {
        font-size: 12px !important;
    }

    #data_consulta {
        color: #2d3748;
        text-align: center;
        padding: 5px;
    }

    .label-evento {
        padding: 5px 10px;
        border-radius: 3px;
        font-size: 11px;
    }

    .log-xml {
        max-width: 300px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        cursor: pointer;
    }

    .swal-wide {
        max-width: 900px !important;
    }

    .swal-wide textarea {
        width: 100%;
        border: 1px solid #ddd;
        padding: 10px;
        background-color: #f9f9f9;
    }
    
    /* Cabeçalho da tabela com tema de log */
    #datatable-buttons thead tr {
        background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%) !important;
    }
    
    /* Labels de origem diferenciadas */
    .label-info {
        background-color: #3498db !important;
    }
    
    .label-primary {
        background-color: #5a6c7d !important;
    }
    
    .label-success {
        background-color: #718096 !important;
    }
    
    /* Botões da toolbar */
    .x_title .btn-default {
        background: #718096;
        color: white;
        border: none;
    }
    
    .x_title .btn-default:hover {
        background: #5a6c7d;
    }
    
    .x_title .btn-warning {
        background: #f59e0b;
        color: white;
        border: none;
    }
    
    .x_title .btn-warning:hover {
        background: #d97706;
    }
</style>

<script type="text/javascript" src="{$pathJs}/est/s_nota_fiscal_servico.js"></script>
<script type="text/javascript" src="{$pathSweet}/dist/sweetalert2.all.min.js"></script>

<!-- page content -->
<div class="right_col" role="main">
    <!-- panel principal  -->
    <div class="col-md-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Log de Nota Fiscal de Serviço
                    <small>Consulta de Eventos</small>
                </h2>

                <ul class="nav navbar-right panel_toolbox">
                    <!-- BTN VOLTAR -->
                    <li>
                        <button type="button" class="btn btn-default" onClick="javascript:logBackNFS();">
                            <span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span>
                            <span>Voltar</span>
                        </button>
                    </li>

                    <!-- BTN PESQUISA -->
                    <li>
                        <button type="button" class="btn btn-warning" onClick="javascript:logSubmitSearch();">
                            <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                            <span>Pesquisar</span>
                        </button>
                    </li>
                </ul>

                <div class="clearfix"></div>
            </div>

            <div class="x_content">
                <form id="lancamento" name="lancamento" METHOD="POST" class="form-horizontal form-label-left" ACTION={$SCRIPT_NAME}>
                    <input name="mod"     type="hidden" value="est">
                    <input name="form"    type="hidden" value="nota_fiscal_servico">
                    <input name="submenu" type="hidden" value="log">
                    <input name="opcao"   type="hidden" value="">
                    <input name="data_ini" type="hidden" value="{$data_ini}">
                    <input name="data_fim" type="hidden" value="{$data_fim}">
                    
                    <div class="row">

                        <!-- CLIENTE -->
                        <div class="col-md-6">
                            <label for="cliente">Cliente</label>
                            <div class="input-group">
                                <input type="text" class="form-control input-sm" id="cliente_nome" name="cliente_nome" placeholder="Nome ou CPF/CNPJ do cliente" readonly value="{$cliente_nome}">
                                <input type="hidden" id="cliente_id" name="cliente_id" value="{$cliente_id}">
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-primary btn-sm" 
                                            onClick="javascript:logOpenNewWindow('{$pathCliente}/index.php?mod=crm&form=contas&opcao=pesquisarRelatorios');">
                                        <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                                    </button>
                                </span>
                            </div>
                        </div>

                        <!-- PERIODO -->
                        <div class="col-md-3">
                            <label for="data_consulta">Período:</label>
                            <input type="text" class="form-control input-sm" id="data_consulta" name="data_consulta" readonly value="{$data_ini} - {$data_fim}">
                        </div>

                        <!-- ORIGEM -->
                        <div class="col-md-3">
                            <label for="origem">Origem</label>
                            <select class="form-control input-sm" id="origem" name="origem">
                                <option value="">Todas</option>
                                <option value="OS" {if $origem eq 'OS'}selected{/if}>Ordem de Serviço</option>
                                <option value="PED" {if $origem eq 'PED'}selected{/if}>Pedido</option>
                                <option value="NFS" {if $origem eq 'NFS'}selected{/if}>NFS</option>
                            </select>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- panel tabela dados -->
    <div class="col-md-12 col-xs-12">
        <div class="x_panel">
            <div class="x_content">
                <table id="datatable-buttons" class="table table-bordered jambo_table table-striped">
                    <thead>
                        <tr style="color: white;">
                            <th class="small text-center" style="width: 10px;">ID</th>
                            <th class="small text-center" style="width: 120px;">Data/Hora</th>
                            <th class="small text-center" style="width: 50px;">Série</th>
                            <th class="small">Cliente</th>
                            <th class="small text-center" style="width: 50px;">Origem</th>
                            <th class="small text-center" style="width: 90px;">Cód. Retorno</th>
                            <th class="small text-center" style="width: 100px;">Usuário</th>
                            <th class="small text-center" style="width: 80px;">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        {if $logs}
                            {section name=i loop=$logs}
                                <tr>
                                    <td class="small text-center">{$logs[i].ID}</td>
                                    <td class="small text-center">{$logs[i].CREATED_AT|date_format:"%d/%m/%Y %H:%M"}</td>
                                    <td class="small text-center">{$logs[i].SERIE|default:'-'}</td>
                                    <td class="small">{$logs[i].CLIENTE_NOME|default:'N/A'}</td>
                                    <td class="small text-center">
                                        <span class="label {if $logs[i].ORIGEM eq 'OS'}label-info{elseif $logs[i].ORIGEM eq 'PED'}label-primary{elseif $logs[i].ORIGEM eq 'NFS'}label-success{else}label-default{/if}">
                                            {$logs[i].ORIGEM|default:'-'}
                                        </span>
                                    </td>
                                    <td class="small text-center">
                                        {$logs[i].CODIGO_RETORNO|default:'-'}
                                    </td>
                                    <td class="small text-center">
                                        {$logs[i].USUARIO_NOME|default:'Sistema'}
                                    </td>
                                    <td class="small text-center">
                                        <button type="button" title="Ver XML" class="btn btn-info btn-xs" onClick="javascript:LogViewXML('{$logs[i].ID}');">
                                            <span class="glyphicon glyphicon-file" aria-hidden="true"></span>
                                        </button>
                                    </td>
                                </tr>
                            {/section}
                        {else}
                            <tr>
                                <td colspan="10" style="text-align: center; padding: 20px;">
                                    <i class="fa fa-info-circle"></i> Nenhum log encontrado para os filtros selecionados.
                                </td>
                            </tr>
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
    $('input[name="data_consulta"]').daterangepicker({
            startDate: moment("{$data_ini}", "DD/MM/YYYY"),
            endDate: moment("{$data_fim}", "DD/MM/YYYY"),
            ranges: {
                'Hoje': [moment(), moment()],
                'Ontem': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Últimos 7 Dias': [moment().subtract(6, 'days'), moment()],
                'Últimos 30 Dias': [moment().subtract(29, 'days'), moment()],
                'Este Mês': [moment().startOf('month'), moment().endOf('month')],
                'Último Mês': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
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
        function(start, end, label) {
            var f = document.lancamento;
            if (f.data_ini && f.data_fim) {
                f.data_ini.value = start.format('DD/MM/YYYY');
                f.data_fim.value = end.format('DD/MM/YYYY');
            }
        });
</script>

