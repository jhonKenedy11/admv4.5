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


    .result-panel {
        background: #fff;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 20px;
    }

    .table-responsive {
        border-radius: 5px;
        overflow: hidden;
    }

    .search-panel .form-group {
        margin-bottom: 8px;
    }

    .search-panel .form-group label {
        font-weight: 600;
        color: #495057;
        margin-bottom: 5px;
        display: block;
    }

    .search-panel .form-control {
        height: 38px;
        font-size: 14px;
    }

    .search-panel .btn-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    /* Responsividade para telas menores */
    @media (max-width: 768px) {
        .search-panel .row > div {
            margin-bottom: 15px;
        }
        
        .search-panel .btn-group {
            flex-direction: row;
            justify-content: center;
        }
        
        .search-panel .btn-group .btn {
            min-width: 120px;
        }
        
        /* Ajustes específicos para os botões de ação em telas pequenas */
        #divAcao .btn {
            width: 100%;
            margin-bottom: 10px;
        }
    }

    /* Melhorias visuais para o painel */
    .search-panel {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border: 1px solid #dee2e6;
        border-radius: 12px;
        padding: 13px;
        padding-bottom: 5px;
        margin-bottom: 25px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }


    .status-badge {
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: bold;
        text-transform: uppercase;
    }

    .status-pedido {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    .status-os {
        background: #d1ecf1;
        color: #0c5460;
        border: 1px solid #bee5eb;
    }
</style>

<script src="{$bootstrap}/jquery/dist/jquery.3.5.1.min.js"></script>
<script type="text/javascript" src="{$pathJs}/est/s_faturamento.js"> </script>

<div class="right_col" role="main">
    <div class="">
        <div class="row">
            <div class="col-md-12 col-xs-12">
                <div class="x_panel">
                
                    <div class="x_title">
                        <h2><i class="fa fa-search"></i> Pesquisa de Faturas NFS</h2>
                        <div class="clearfix"></div>
                    </div>
                    
                    <div class="x_content">
                        <!-- Formulário de Pesquisa -->
                        <form id="lancamento" name="lancamento" data-parsley-validate METHOD="POST"
                            class="form-horizontal form-label-left" ACTION={$SCRIPT_NAME}>
                            
                            <!-- Campos Hidden -->
                            <input name=mod type=hidden value="{$mod}">
                            <input name=form type=hidden value="{$form}">
                            <input name=opcao type=hidden value="">
                            <input name=id type=hidden value="">
                            <input name=submenu type=hidden value={$subMenu}>
                            <input name=cliente_id type=hidden value={$cliente_id}>

                            <!-- Painel de Pesquisa -->
                            <div class="search-panel">
                                <div class="row">
                                    <!-- Número do Documento -->
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Documento</label>
                                            <input type="text" class="form-control" name="document_id" 
                                                   placeholder="Nº do documento">
                                        </div>
                                    </div>

                                    <!-- Período de Data -->
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Período</label>
                                            <input type="text" class="form-control" id="data_consulta" name="data_consulta" placeholder="Selecione o período" readonly>
                                        </div>
                                    </div>
                                    
                                    <!-- Cliente -->
                                    <div class="col-md-7">
                                        <div class="form-group">
                                            <label>Cliente</label>
                                            <select class="form-control select2" name="cliente" id="cliente">
                                                <option value="">Selecione o cliente</option>
                                                <!-- Opções de clientes serão carregadas via AJAX -->
                                            </select>
                                        </div>
                                    </div>
                                    
                                </div>
                            
                                <div class="row" id="divAcao">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <!-- Espaçador à esquerda -->
                                            <div class="col-md-3"></div>
                                            
                                            <!-- Botão Pesquisar centralizado -->
                                            <div class="col-md-6 text-center">
                                                <button style="width: 100%;" type="button" class="btn btn-primary btn-sm" id="btnPesquisar" title="Pesquisar" onclick="searchDocuments()">
                                                    <i class="fa fa-search"></i> Pesquisar
                                                </button>
                                            </div>
                                            
                                            <!-- Botão Limpar à direita -->
                                            <div class="col-md-3 text-right">
                                                <button type="button" class="btn btn-sm btn-warning" id="btnLimpar" title="Limpar filtros" onclick="limparPesquisa()">
                                                    <i class="fa fa-times"></i> Limpar
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <!-- Painel de Resultados -->
                            <div class="result-panel">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h4><i class="fa fa-list"></i> Resultados da Pesquisa</h4>
                                        <hr>
                                        
                                        <!-- Tabela de Resultados -->
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered table-hover" id="tabelaResultados">
                                                <thead>
                                                    <tr>
                                                        <th width="12%"><center>Data</center></th>
                                                        <th width="12%"><center>Nº Documento</center></th>
                                                        <th width="12%"><center>Tipo</center></th>
                                                        <th>Cliente</th>
                                                        <th width="13%" style="text-align: right;">Valor Total</th>
                                                        <th width="10%"><center>Status</center></th>
                                                        <th width="12%"><center>Ações</center></th>
                                                    </tr>
                                                </thead>
                                                <tbody id="corpoTabela">
                                                    <!-- Dados serão carregados via AJAX -->
                                                    <tr>
                                                        <td colspan="8" class="text-center text-muted">
                                                            <i class="fa fa-info-circle"></i> 
                                                            Utilize os filtros acima para realizar uma pesquisa
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        
                                        <!-- Paginação -->
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="dataTables_info" id="infoPagina">
                                                    Mostrando 0 a 0 de 0 registros
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="dataTables_paginate paging_simple_numbers" id="paginacao">
                                                    <!-- Paginação será gerada via JavaScript -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{include file="template/database.inc"}

<!-- Include da Modal de Serviços -->
{include file="modal_servicos.tpl"}

<!-- daterangepicker -->
<script src="js/moment/moment.min.js"></script>
<script src="js/datepicker/daterangepicker.js"></script>


<script>
$(document).ready(function() {

    // Inicialização do DateRangePicker
    $('input[name="data_consulta"]').daterangepicker({
        startDate: moment("{$dataIni}", "DD/MM/YYYY").isValid() ? moment("{$dataIni}", "DD/MM/YYYY") : moment().startOf('month'),
        endDate: moment("{$dataFim}", "DD/MM/YYYY").isValid() ? moment("{$dataFim}", "DD/MM/YYYY") : moment().endOf('month'),
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
    }, function(start, end, label) {
        // Callback quando as datas são selecionadas
        //console.log('Período selecionado:', start.format('DD/MM/YYYY'), 'até', end.format('DD/MM/YYYY'));
    });

    // Função para garantir que sempre inicie com o mês atual se não houver datas definidas
    function garantirMesAtual() {
        var dataConsulta = $('input[name="data_consulta"]').val();
        if (!dataConsulta || dataConsulta.trim() === '') {
            var inicioMes = moment().startOf('month').format('DD/MM/YYYY');
            var fimMes = moment().endOf('month').format('DD/MM/YYYY');
            $('input[name="data_consulta"]').val(inicioMes + ' - ' + fimMes);
        }
    }

    // Executa a verificação ao carregar a página
    garantirMesAtual();

});
</script>


