<link href="{$bootstrap}/select2-master/dist/css/select2.min.css" rel="stylesheet">
<style>
.form-control, .x_panel { border-radius: 5px; }
</style>

<div class="right_col" role="main" style="padding: 14px;">
    <div class="row">
        <div class="col-md-12 col-xs-12" style="padding: 1px;">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Consulta Cupons PDV</h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li>
                            <button type="submit" class="btn btn-warning" form="lancamento" name="consultar" value="1">
                                <span class="glyphicon glyphicon-zoom-in" aria-hidden="true"></span>
                                <span> Pesquisa</span>
                            </button>
                        </li>
                        <li>
                            <button type="button" class="btn btn-primary" onclick="pdvListaNovo();">
                                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                                <span> Novo cupom</span>
                            </button>
                        </li>
                    </ul>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    {if $mensagem neq ''}
                        <div class="alert alert-{if $tipoMsg eq 'erro'}danger{elseif $tipoMsg eq 'alerta'}warning{else}success{/if} alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                            {$mensagem}
                        </div>
                    {/if}

                    <form id="lancamento" name="lancamento" method="post" action="{$SCRIPT_NAME}">
                        <input type="hidden" name="mod" value="pdv">
                        <input type="hidden" name="form" value="cupom">
                        <input type="hidden" name="submenu" value="">
                        <input type="hidden" name="id" value="">
                        <input type="hidden" name="dataIni" value="{$dataIni}">
                        <input type="hidden" name="dataFim" value="{$dataFim}">

                        <div class="form-group">
                            <div class="col-md-2 col-sm-4 col-xs-6">
                                <label for="idFiltro">Pedido</label>
                                <input type="text" class="form-control input-sm" id="idFiltro" name="idFiltro"
                                    value="{$idFiltro}" placeholder="ID do cupom">
                            </div>
                            <div class="col-md-4 col-sm-6 col-xs-12">
                                <label>Período</label>
                                <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                                <input type="text" name="dataConsulta" id="dataConsulta" class="form-control input-sm"
                                    value="{$dataIni} - {$dataFim}">
                            </div>
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <label for="clientePdvLista">Cliente</label>
                                <select id="clientePdvLista" name="pessoa" class="form-control input-sm" style="width:100%;">
                                    {if $pessoa neq '' && $nomeCliente neq ''}
                                        <option value="{$pessoa}" selected="selected">{$nomeCliente}</option>
                                    {/if}
                                </select>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </form>

                    <table class="table table-striped table-bordered jambo_table">
                        <thead>
                            <tr class="headings">
                                <th>ID</th>
                                <th>Emissão</th>
                                <th>Cliente</th>
                                <th>Itens</th>
                                <th>Total</th>
                                <th>Operador</th>
                                <th>NFC-e</th>
                                <th>Opções</th>
                            </tr>
                        </thead>
                        <tbody>
                            {if $lanc|@count eq 0}
                                <tr>
                                    <td colspan="8" class="text-center text-muted">Nenhum cupom PDV em aberto para os filtros informados.</td>
                                </tr>
                            {else}
                                {section name=i loop=$lanc}
                                    <tr>
                                        <td>{$lanc[i].ID}</td>
                                        <td>{$lanc[i].EMISSAO_FMT}</td>
                                        <td>{$lanc[i].NOMECLIENTE|default:'—'}</td>
                                        <td class="text-center">{$lanc[i].QTDITENS}</td>
                                        <td>R$ {$lanc[i].TOTAL_FMT}</td>
                                        <td>{$lanc[i].NOMEUSUARIO|default:$lanc[i].USERINSERT}</td>
                                        <td>
                                            {if $lanc[i].TEM_NFCE eq 'S'}
                                                <span class="label label-warning">Emitida</span>
                                            {else}
                                                <span class="label label-success">Aberto</span>
                                            {/if}
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-primary btn-xs"
                                                onclick="pdvListaEditar({$lanc[i].ID});" title="Editar">
                                                <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                                            </button>
                                            <button type="button" class="btn btn-danger btn-xs"
                                                onclick="pdvListaExcluir({$lanc[i].ID});" title="Excluir">
                                                <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
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
</div>

{include file="template/form.inc"}

<script src="js/moment/moment.min.js"></script>
<script src="js/datepicker/daterangepicker.js"></script>
<script src="{$bootstrap}/select2-master/dist/js/select2.full.min.js"></script>
<script type="text/javascript">
$(document).ready(function () {
    var urlCliente = '{$SCRIPT_NAME}?mod=pdv&form=cupom&submenu=pesquisaClienteAjax&opcao=blank';
    $('#clientePdvLista').select2({
        placeholder: 'Digite para buscar cliente (mín. 3 caracteres)',
        allowClear: true,
        minimumInputLength: 3,
        width: '100%',
        ajax: {
            dataType: 'json',
            delay: 250,
            url: urlCliente,
            data: function (params) {
                return { term: params.term };
            },
            processResults: function (data) {
                return { results: data || [] };
            }
        }
    });
});
</script>
<script type="text/javascript" src="{$pathJs}/pdv/s_cupom_pdv.js"></script>
<script type="text/javascript">
    $('input[name="dataConsulta"]').daterangepicker({
        startDate: moment("{$dataIni}", "DD/MM/YYYY"),
        endDate: moment("{$dataFim}", "DD/MM/YYYY"),
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
                'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
            firstDay: 1
        }
    }, function (start, end) {
        var f = document.lancamento;
        if (f && f.dataIni && f.dataFim) {
            f.dataIni.value = start.format('DD/MM/YYYY');
            f.dataFim.value = end.format('DD/MM/YYYY');
        }
    });
</script>
