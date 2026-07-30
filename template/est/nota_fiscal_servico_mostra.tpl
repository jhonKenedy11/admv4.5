<style>
    .x_panel,
    .form-control {
        border-radius: 5px;
    }

    .x_panel {
        border-left: 4px solid rgb(65, 108, 109);
        background: #f8f9fa;
    }

    .right_col {
        padding: 1px !important;
    }

    #msgRetorno {
        font-size: 12px !important;
    }

    #data_consulta {
        color: #333;
        font-weight: bold;
        text-align: center;
        padding: 5px;
        border-radius: 5px;
    }
    /* Título com identidade de LOG */
    .x_title {
        background: linear-gradient(135deg, rgb(65, 108, 109) 0%, #2d3748 100%);
        color: white !important;
        padding: 15px;
        border-radius: 5px 5px 0 0;
        margin: -1px -1px 15px -1px;
    }
    .x_title h2 small {
        color: #cbd5e0 !important;
    }
    /* Estilos mínimos da Modal de Manutenção */
    #modalManutencao .modal-dialog {
        max-width: 420px;
        margin: 30px auto;
    }
    #modalManutencao .modal-header {
        background: linear-gradient(135deg, rgb(65, 108, 109) 0%, #2d3748 100%);
        color: white;
    }
    #modalManutencao .modal-header .close {
        color: white;
        opacity: 0.9;
    }
    #modalManutencao .modal-header .close:hover {
        opacity: 1;
    }
    .swal-input-custom {
        font-size: 14px; /* textarea */
    }
    .swal-title-custom {
        font-size: 17px; /* tamanho do título */
        font-weight: bold;
    }
    
    .swal-text-custom {
        font-size: 15px; /* texto da mensagem */
    }
    .swal-confirm-custom, .swal-cancel-custom {
        font-size: 13px; /* botões */
    }
</style>

<script type="text/javascript" src="{$pathJs}/est/s_nota_fiscal_servico.js"></script>
<script type="text/javascript" src="{$pathSweet}/dist/sweetalert2.all.min.js"></script>

<!-- page content -->
<div class="right_col" role="main">
            <!-- panel principal  -->
            <div class="col-md-12 col-xs-12"">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Nota Fiscal de Serviço
                            <small>Consulta</small>
                        </h2>

                        <ul class="nav navbar-right panel_toolbox">
                            <li>
                                <button type="button" class="btn btn-warning" onClick="javascript:submitSearch();">
                                    <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                                    <span>Pesquisa</span>
                                </button>
                            </li>
                            <li>
                                <button type="button" class="btn btn-primary" onClick="javascript:submitRegister();">
                                    <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                                    <span>Cadastro</span>
                                </button>
                            </li>

                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                                <ul class="dropdown-menu" role="menu">
                                <li>
                                    <button type="button" class="btn btn-dark btn-xs" onClick="javascript:viewLogNFS();"><span> Log NFS</span></button>
                                </li>
                                </ul>
                            </li>
                           
                        </ul>
                        <div class="clearfix"></div>
                    </div>

                    <div class="x_content">
                        <form id="lancamento" name="lancamento" METHOD="POST" class="form-horizontal form-label-left" ACTION={$SCRIPT_NAME}>
                            <input name="mod"     type="hidden" value="est">
                            <input name="form"    type="hidden" value="nota_fiscal_servico">
                            <input name="id"      type="hidden" value="">
                            <input name="opcao"   type="hidden" value="">
                            <input name="submenu" type="hidden" value="{$submenu}">
                            
                            <div class="row">

                                <div class="col-md-8">
                                    <label for="cliente">Cliente</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control input-sm" id="cliente_nome" name="cliente_nome" placeholder="Nome ou CPF/CNPJ do cliente" value="">
                                        <input type="hidden" id="cliente_id" name="cliente_id" value="">
                                        <span class="input-group-btn ">
                                            <button type="button" class="btn btn-primary btn-sm" 
                                                    onClick="javascript:abrir('{$pathCliente}/index.php?mod=crm&form=contas&opcao=pesquisarRelatorios');">
                                                <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                                            </button>
                                        </span>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <label for="centro_custo">Centro de Custo</label>
                                    <select class="form-control input-sm" id="centro_custo" name="centro_custo">
                                        {html_options values=$centro_custo_ids selected=$centro_custo_id output=$centro_custo_names}
                                    </select>
                                </div>
                                
                            </div>

                            <div class="row">

                                <div class="col-md-2">
                                    <label for="numero_nfs">Número NFS</label>
                                    <input type="text" class="form-control input-sm" id="numero_nfs" name="numero_nfs" placeholder="" maxlength="10">
                                </div>

                                <div class="col-md-1 offset-md-1"> </div>


                                <div class="col-md-4">
                                    <label for="situacao_nfs">Situacao</label>
                                    <select class="form-control input-sm" id="situacao_nfs" name="situacao_nfs">
                                        {html_options values=$situacao_ids selected=$situacao_id output=$situacao_names}
                                    </select>
                                </div>

                                <div class="col-md-1 offset-md-1"> </div>

                                <div class="col-md-4">
                                    <label for="data_consulta">Período:</label>
                                    <input type="text" class="form-control input-sm" id="data_consulta" name="data_consulta" readonly value="{$data_ini} - {$data_fim}">
                                </div>

                            </div>
                        </form>
                    </div>
                </div>
            </div>

        <!-- panel tabela dados -->
        <div class="col-md-12 col-xs-12">
        <div class="x_panel">
                <table id="datatable-buttons" class="table table-bordered jambo_table">
                    <thead>
                        <tr class="headings">
                            <th class="small text-center" style="width: 30px;">Número</th>
                            <th class="small text-center">Cliente</th>
                            <th class="small text-center" style="width: 100px;">Centro de Custo</th>
                            <th class="small text-center" style="width: 105px;">Emissão</th>
                            <th class="small text-center" style="width: 85px;">CPF/CNPJ</th>
                            <th class="small text-center" style="width: 70px;">Total</th>
                            <th class="small text-center" style="width: 50px;">Status</th>
                            <th class="small text-center" style="width: 100px;"></th>
                        </tr>
                    </thead>

                    <tbody>
                        {if $notasFiscais}
                            {section name=i loop=$notasFiscais}
                                <tr>
                                    <td class="small text-center"> {$notasFiscais[i].NUMERO} </td>
                                    <td class="small text-center"> {$notasFiscais[i].NOME_CLIENTE} </td>
                                    <td class="small text-center"> {$notasFiscais[i].CENTRO_CUSTO_DESCRICAO} </td>
                                    <td class="small text-center"> {$notasFiscais[i].DATA_EMISSAO|date_format:"%Y-%m-%d"} {$notasFiscais[i].HORA_EMISSAO|date_format:"%H:%M:%S"}</td>
                                    <td class="small text-center"> {$notasFiscais[i].TOMADOR_CPFCNPJ} </td>
                                    <td class="small text-center"> R$ {$notasFiscais[i].VALOR_TOTAL|number_format:2:',':'.'} </td>
                                    <td class="text-center"> 
                                        <span class="label label-{if $notasFiscais[i].SITUACAO eq 0}warning{elseif $notasFiscais[i].SITUACAO eq 1}success{elseif $notasFiscais[i].SITUACAO eq 2}danger{elseif $notasFiscais[i].SITUACAO eq 3}default{else}info{/if}">
                                            {if $notasFiscais[i].SITUACAO eq 0}Aberta{elseif $notasFiscais[i].SITUACAO eq 1}Emitida{elseif $notasFiscais[i].SITUACAO eq 2}Cancelada{elseif $notasFiscais[i].SITUACAO eq 3}Devolvida{else}Solicitação de cancelamento{/if}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" title="Editar" disabled class="btn btn-primary btn-xs" onClick="javascript:editInvoice('{$notasFiscais[i].ID}');">
                                            <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                                        </button>
                                        <button type="button" title="imprimir" class="btn btn-success btn-xs" onClick="javascript:printInvoice('{$notasFiscais[i].LINK_NFSE}');">
                                            <span class="glyphicon glyphicon-print" aria-hidden="true"></span>
                                        </button>
                                        <button type="button" title="Menu de Manutenção" class="btn btn-default btn-xs" onClick="javascript:openMaintenanceModal('{$notasFiscais[i].ID}', '{$notasFiscais[i].SITUACAO}');">
                                            <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
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

<!-- Modal de Manutenção -->
<div class="modal fade" id="modalManutencao" tabindex="-1" role="dialog" aria-labelledby="modalManutencaoLabel">
    <!-- input hidden para o id da nota fiscal -->
    <input type="hidden" id="id_modal" name="id_modal" value="">

    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="modalManutencaoLabel">
                    <span class="glyphicon glyphicon-cog"></span> Manutenção
                </h4>
            </div>
            <div class="modal-body">

                <button type="button" disabled class="btn btn-info btn-block mb-2" id="btnConsulta" onClick="javascript:consultInvoice( document.getElementById('id_modal').value );" disabled>
                    <span class="glyphicon glyphicon-search"></span> Consulta NFS-e na Prefeitura
                </button>

                <button type="button" class="btn btn-danger btn-block mb-2" id="btnCancelar" onClick="javascript:cancelInvoice( document.getElementById('id_modal').value );" disabled>
                    <span class="glyphicon glyphicon-remove"></span> Cancelar NFS-e na Prefeitura
                </button>

                <button type="button" class="btn btn-info btn-block mb-2" id="btnView" onClick="javascript:viewInvoiceFromModal();" disabled>
                    <span class="glyphicon glyphicon-eye-open"></span> Visualizar
                </button>
                
                <button type="button" class="btn btn-danger btn-block mb-2" id="btnDelete" onClick="javascript:deletInvoiceFromModal('{$notasFiscais[i].ID}');">
                    <span class="glyphicon glyphicon-trash"></span> Deletar
                </button>

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
            if (f.data_ini && f.data_fim) {
                f.data_ini.value = start.format('DD/MM/YYYY');
                f.data_fim.value = end.format('DD/MM/YYYY');
            }
        });
</script> 

<!-- Include do Modal de Serviços -->
{include file="modal_servicos.tpl"} 