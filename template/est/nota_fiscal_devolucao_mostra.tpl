<style>
    .form-control{ border-radius: 5px !important; }
</style>
<div class="right_col" role="main">
    <div class="row">
        <div class="col-md-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Devolução de Nota Fiscal <small>Consulta</small></h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li>
                            <button type="button" class="btn btn-warning btn-sm" onclick="submitLetraDevolucao();">
                                <span class="glyphicon glyphicon-zoom-in"></span> Pesquisa
                            </button>
                        </li>
                        <li>
                            <button type="button" class="btn btn-primary btn-sm" onclick="cadastrarDevolucao();">
                                <span class="glyphicon glyphicon-plus"></span> Cadastro
                            </button>
                        </li>
                        
                    </ul>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <form id="formDevolucaoMostra" name="formDevolucaoMostra" method="POST" action="{$SCRIPT_NAME}" class="form-horizontal form-label-left">
                        <input type="hidden" name="mod" value="est">
                        <input type="hidden" name="form" value="nota_fiscal_devolucao">
                        <input type="hidden" name="submenu" value="mostra">
                        <input type="hidden" name="letra" value="{$letra}">
                        <input type="hidden" name="pessoa" value="{$pessoa}">
                        <input type="hidden" name="dataIni" value="{$dataIni}">
                        <input type="hidden" name="dataFim" value="{$dataFim}">
                        <input type="hidden" name="genero" value="{$genero}">
                        <input type="hidden" name="transportador" value="{$transportador}">

                        <div class="form-group col-md-2 col-sm-12 col-xs-12">
                            <label>Número NF</label>
                            <input class="form-control input-sm" name="numNf" placeholder="Número da nota fiscal a pesquisar" value="{$numNf}">
                        </div>
                        <div class="form-group col-md-2 col-sm-12 col-xs-12">
                            <label>Série</label>
                            <input class="form-control input-sm" name="serieNf" placeholder="Série da nota fiscal a pesquisar" value="{$serieNf}">
                        </div>
                        <div class="form-group col-md-2 col-sm-12 col-xs-12">
                            <label>Modelo</label>
                            <input class="form-control input-sm" name="modeloNf" placeholder="Modelo da nota fiscal a pesquisar" value="{$modeloNf}">
                        </div>
                        <div class="col-md-3 col-sm-12 col-xs-12 has-feedback">
                            <label>Período</label>
                            <input type="text" name="dataConsulta" id="dataConsulta" class="form-control input-sm" placeholder="Período de emissão" value="{$dataConsulta}">
                        </div>
                        <div class="form-group col-md-3 col-sm-12 col-xs-12">
                            <label>Situação</label>
                            <select class="form-control input-sm" name="msituacao">
                                {html_options values=$situacao_ids output=$situacao_names selected=$situacao_id}
                            </select>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-md-6 col-sm-12 col-xs-12">
                            <label>Pessoa</label>
                            <div class="input-group">
                                <input type="text" class="form-control input-sm" name="nome" placeholder="Nome da pessoa" value="{$nome}">
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-primary btn-sm"
                                        onclick="javascript:abrir('{$pathCliente}/index.php?mod=crm&form=contas&opcao=pesquisar');">
                                        <span class="glyphicon glyphicon-search"></span>
                                    </button>
                                </span>
                            </div>
                        </div>
                        <div class="form-group col-md-3 col-sm-12 col-xs-12">
                            <label>Tipo Nota Fiscal</label>
                            <select class="form-control input-sm" name="mtipo">
                                {html_options values=$tipo_ids output=$tipo_names selected=$tipo_id}
                            </select>
                        </div>
                        <div class="form-group col-md-3 col-sm-12 col-xs-12">
                            <label>Empresa</label>
                            <select class="form-control input-sm" name="mfilial">
                                {html_options values=$filial_ids output=$filial_names selected=$filial_id}
                            </select>
                        </div>

                        <div class="accordion" id="accordionDevolucao" role="tablist">
                            <div class="panel">
                                <a class="panel-heading collapsed" role="tab" data-toggle="collapse" href="#filtrosAvancadosDevolucao">
                                    <h4 class="panel-title">Filtros avançados <i class="fa fa-chevron-down"></i></h4>
                                </a>
                                <div id="filtrosAvancadosDevolucao" class="panel-collapse collapse">
                                    <div class="panel-body">
                                        <div class="form-group col-md-4 col-sm-12 col-xs-12">
                                            <label>Natureza Operação</label>
                                            <select name="idNatop" class="form-control input-sm" title="Natureza de operação">
                                                {html_options values=$natOperacao_ids output=$natOperacao_names selected=$natOperacao_id}
                                            </select>
                                        </div>
                                        <div class="form-group col-md-4 col-sm-12 col-xs-12">
                                            <label>Finalidade Emissão</label>
                                            <select name="finalidadeEmissao" class="form-control input-sm" title="Finalidade de emissão">
                                                {html_options values=$finalidadeEmissao_ids output=$finalidadeEmissao_names selected=$finalidadeEmissao_id}
                                            </select>
                                        </div>
                                        <div class="form-group col-md-4 col-sm-12 col-xs-12">
                                            <label>Modalidade Frete</label>
                                            <select name="modFrete" class="form-control input-sm" title="Modalidade de frete">
                                                {html_options values=$modFrete_ids output=$modFrete_names selected=$modFrete_id}
                                            </select>
                                        </div>
                                        <div class="form-group col-md-6 col-sm-12 col-xs-12">
                                            <label>Gênero</label>
                                            <div class="input-group">
                                                <input readonly type="text" class="form-control input-sm" name="descgenero" placeholder="Selecione o gênero" value="{$descGenero}">
                                                <span class="input-group-btn">
                                                    <button type="button" class="btn btn-primary btn-sm"
                                                        onclick="javascript:abrir('{$pathCliente}/index.php?mod=fin&form=genero&opcao=pesquisar');">
                                                        <span class="glyphicon glyphicon-search"></span>
                                                    </button>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6 col-sm-12 col-xs-12">
                                            <label>Transportador</label>
                                            <div class="input-group">
                                                <input readonly type="text" class="form-control input-sm" name="transpNome" placeholder="Transportador que realiza o frete" value="{$transpNome}">
                                                <span class="input-group-btn">
                                                    <button type="button" class="btn btn-primary btn-sm"
                                                        onclick="javascript:abrir('{$pathCliente}/index.php?mod=crm&form=contas&opcao=pesquisartransportador');">
                                                        <span class="glyphicon glyphicon-search"></span>
                                                    </button>
                                                </span>
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

    <div class="col-md-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Notas fiscais para devolução</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content table-responsive">
                <table id="datatable-nf-origem" class="table table-bordered jambo_table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Emissão</th>
                            <th>NF</th>
                            <th>Pessoa</th>
                            <th>Natureza Operação</th>
                            <th>Tipo</th>
                            <th>Situação</th>
                            <th>Total</th>
                            <th width="90">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        {section name=i loop=$lancOrigem}
                        <tr>
                            <td>{$lancOrigem[i].ID}</td>
                            <td>{$lancOrigem[i].EMISSAO|date_format:"%d/%m/%Y"}</td>
                            <td>{$lancOrigem[i].NUMERO}</td>
                            <td>{$lancOrigem[i].NOMEREDUZIDO}</td>
                            <td>{$lancOrigem[i].NATOPERACAO|default:'-'}</td>
                            <td>{if $lancOrigem[i].TIPO == '0' || $lancOrigem[i].TIPO == 0}Entrada{elseif $lancOrigem[i].TIPO == '1' || $lancOrigem[i].TIPO == 1}Saída{else}{$lancOrigem[i].TIPONOTA|regex_replace:"/^\d+\s*-\s*/":""|capitalize}{/if}</td>
                            <td>{$lancOrigem[i].SITUACAONOTA}</td>
                            <td>{$lancOrigem[i].TOTALNF}</td>
                            <td>
                                <button type="button" class="btn btn-xs btn-danger"
                                    onclick="devolverNfLinha({$lancOrigem[i].ID});">
                                    <i class="fa fa-reply"></i> Devolver
                                </button>
                            </td>
                        </tr>
                        {sectionelse}
                        <tr><td colspan="9" class="text-center">Pesquise para listar notas fiscais elegíveis à devolução.</td></tr>
                        {/section}
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Devoluções registradas</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content table-responsive">
                <table id="datatable-buttons" class="table table-bordered jambo_table bulk_action">
                    <thead>
                        <tr>
                            <th>Número</th>
                            <th>Emissão</th>
                            <th>Pessoa</th>
                            <th>Natureza Operação</th>
                            <th>Total</th>
                            <th>Situação</th>
                            <th width="110">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        {section name=d loop=$lanc}
                        <tr>
                            <td>{$lanc[d].NUMERO}{if $lanc[d].SERIE neq ''}/{$lanc[d].SERIE}{/if}</td>
                            <td>{$lanc[d].EMISSAO|date_format:"%d/%m/%Y"}</td>
                            <td>{$lanc[d].NOME}</td>
                            <td>{$lanc[d].NATOPERACAO|default:'-'}</td>
                            <td>{$lanc[d].TOTALNF}</td>
                            <td>{$lanc[d].SITUACAONOTA|default:$lanc[d].SITUACAO}</td>
                            <td>
                                {if $lanc[d].SITUACAO eq 'A' && $lanc[d].CHNFE eq ''}
                                <button type="button" class="btn btn-xs btn-info" title="Continuar assistente" onclick="continuarDevolucao({$lanc[d].ID});">
                                    <i class="fa fa-play"></i>
                                </button>
                                {/if}
                                <button type="button" class="btn btn-success btn-xs"
                                    {if $lanc[d].SITUACAO neq 'B'}
                                        disabled title="Disponível após autorização da NF-e"
                                    {else}
                                        title="Nota Fiscal, Boletos e E-mail"
                                    {/if}
                                    onclick="abrirNotaFiscalBoletoBancario('{$lanc[d].ID}');">
                                    <span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span>
                                </button>
                            </td>
                        </tr>
                        {sectionelse}
                        <tr><td colspan="7" class="text-center">Nenhuma devolução encontrada no período.</td></tr>
                        {/section}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{include file="template/database.inc"}

<script type="text/javascript" src="{$pathSweet}/dist/sweetalert2.all.min.js"></script>
<script type="text/javascript" src="{$pathJs}/est/s_nota_fiscal_devolucao.js"></script>
<script>
$(function() {
    $('input[name="dataConsulta"]').daterangepicker({
        startDate: moment("{$dataIni}", "DD/MM/YYYY"),
        endDate: moment("{$dataFim}", "DD/MM/YYYY"),
        ranges: {
            'Hoje': [moment(), moment()],
            'Últimos 7 Dias': [moment().subtract(6, 'days'), moment()],
            'Este Mês': [moment().startOf('month'), moment().endOf('month')]
        },
        locale: { format: 'DD/MM/YYYY', applyLabel: 'Aplicar', cancelLabel: 'Cancelar' }
    }, function(start, end) {
        var f = document.formDevolucaoMostra;
        f.dataIni.value = start.format('DD/MM/YYYY');
        f.dataFim.value = end.format('DD/MM/YYYY');
    });

    $('#formDevolucaoMostra').on('keydown', 'input:not([type=hidden]), select', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            submitLetraDevolucao();
        }
    });
});
</script>
