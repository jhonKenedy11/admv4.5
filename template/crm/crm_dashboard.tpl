<style>
  @media (min-width:768px) {
    [name="valores"] {
      font-size: 40px !important;
    }

    .tile_count .tile_stats_count[name="divsDiario"],
    .tile_count .tile_stats_count[name="divsContato"] {
      color: #73879C;
      font-family: "Helvetica Neue", Roboto, Arial, "Droid Sans", sans-serif;
    }

    [name="divPesquisa"] {
      width: 120px !important;
    }

    .count_top {
      font-size: 18px !important;
    }

    .count_bottom {
      font-size: 14px !important;
      font-family: "Helvetica Neue", Roboto, Arial, "Droid Sans", sans-serif;
    }

    #columnchart_values {
      position: static;
      width: 400px;
      height: 200px;
    }
  }

  .select2-selection--multiple {
    border-radius: 8px !important;
    font-size: 11px;
  }

  li.select2-selection__choice {
    border-radius: 5px !important;
    font-size: 11px;
  }

  li.select2-results__option--highlighted, #dataConsulta {
    border-radius: 8px !important;
    font-size: 11px;
  }

  #dataConsulta,
  .x_panel {
    border-radius: 8px !important;
  }

  #btnSubLet {
    background-color: #1ABB9C;
    color: white;
  }

  #btnSubLet:hover {
    background-color: #04473b;
    -webkit-transition: background-color 0.5s, -webkit-transform 2s;
    transition: background-color 0.5s, transform 2s;
  }

  #labelPesq {
    color: #73879C;
    font-family: "Helvetica Neue", Roboto, Arial, "Droid Sans", sans-serif;
  }

  #acomp {
    overflow: inherit !important;
  }

  #btnAddAcomp {
    border-radius: 16px;
    width: 26px;
    height: 26px;
  }

  #cot {
    overflow: inherit !important;
  }

  #btnAddCot {
    border-radius: 16px;
    width: 26px;
    height: 26px;
  }

  .fa-plus-circle {
    font-size: 15px !important;
  }

  .fa-plus-circle:hover {
    font-size: 17px !important;
    -webkit-transition: font-size 0.5s, -webkit-transform 0.5s;
    transition: transform 0.5s;
    color: #282828 !important;
  }

  .fa-search {
    font-size: 15px !important;
  }

  .fa-search:hover {
    font-size: 17px !important;
    -webkit-transition: 0.5s;
    transition: 0.5s;
    color: #282828 !important;
  }

  #iconesManutencao {
    width: 25px !important;
    align-items: center;
    margin-top: 1px;
    margin-left: 5px;
  }

  #iconesManutencaoCotacao {
    width: 25px !important;
    margin-top: 1px;
    background-color: #282828;
  }

  #iconesManutencaoCotacao:hover {
    background-color: #585858;
  }

  #metaMensal {
    font-size: 12px;
  }

  #percMeta {
    padding: 0;
  }

  #iconePercMeta {
    padding: 0;
    margin-left: -7px;
  }

  .destaque {
    background-color: #1abb9ba8;
    border-radius: 10px;
  }

  #vlres {
    margin-top: -4px !important;
  }

  .qtdTotal {
    margin-left: -9px;
  }

  .calendar {
    height: 21px;
    align-items: center;
  }

  .glyphicon-calendar {
    font-size: 15px !important;
    padding-top: 3px !important;
  }
  .clienteNaoLocalizado{
    margin-top: 30px;
    color: #73879C;
    font-family: "Helvetica Neue", Roboto, Arial, "Droid Sans", sans-serif;
  }

  .status-pill{
    color: #fff;
    border-radius: 7px;
    padding: 3px 8px;
    font-size: 11px;
    font-weight: 600;
  }

  #ulAcompanhamento.is-loading{
    opacity: .55;
    pointer-events: none;
    filter: grayscale(.15);
  }

  #ulCliente.is-loading {
    opacity: .55;
    pointer-events: none;
    filter: grayscale(.15);
  }

  .crm-dash-clientes-hint,
  .crm-dash-acomp-hint {
    padding: 12px 8px;
    color: #73879C;
    font-size: 13px;
    line-height: 1.45;
  }

  /* Cliente selecionado na lista lateral */
  #ulCliente .crm-dash-cliente-item.crm-dash-cliente-selected {
    background-color: #e8f7f3;
    border-left: 4px solid #1ABB9C;
    border-radius: 6px;
    transition: background-color 0.2s ease, border-color 0.2s ease;
  }
  #ulCliente .crm-dash-cliente-item.crm-dash-cliente-selected .title {
    color: #156857;
    font-weight: 600;
  }

  /* Barra de filtros do dashboard CRM */
  .crm-dash-filtros-panel {
    margin-bottom: 12px;
  }
  .crm-dash-filtros-toggle {
    color: #73879C !important;
    text-decoration: none !important;
    display: block;
    width: 100%;
    cursor: pointer;
  }
  .crm-dash-filtros-toggle:hover,
  .crm-dash-filtros-toggle:focus {
    color: #5a7388 !important;
    text-decoration: none !important;
    outline: none;
  }
  .crm-dash-filtros-chevron {
    margin-top: 4px;
    transition: transform 0.2s ease;
  }
  .crm-dash-filtros-toggle.collapsed .crm-dash-filtros-chevron {
    transform: rotate(180deg);
  }
  .crm-dash-filtros-panel .x_content {
    padding-top: 8px;
  }
  .crm-dash-filtro-label {
    display: block;
    font-weight: 600;
    font-size: 12px;
    margin-bottom: 6px;
    color: #73879C;
    font-family: "Helvetica Neue", Roboto, Arial, "Droid Sans", sans-serif;
  }
  .crm-dash-filtros-panel .form-group {
    margin-bottom: 12px;
  }
  .crm-dash-filtros-panel .select2-container {
    width: 100% !important;
  }
  .crm-dash-filtros-panel .crm-dash-filtros-acoes {
    margin-bottom: 0;
    padding-top: 4px;
  }
  @media (max-width: 767px) {
    .crm-dash-filtros-panel .crm-dash-filtros-acoes {
      text-align: left !important;
    }
  }
  #btnSubLet {
    min-width: 128px;
  }

</style>

<form id="dashboardLancamento" name="dashboardLancamento" data-parsley-validate METHOD="POST" class="form-horizontal form-label-left"
  ACTION={$SCRIPT_NAME}>
  <input name=mod type=hidden value="crm">
  <input name=form type=hidden value="crm_acompanhamento_dashboard">
  <input name=id type=hidden value="">
  <input name=idAcomp type=hidden value={$idAcomp}>
  <input name=opcao type=hidden value={$opcao}>
  <input name=submenu type=hidden value={$subMenu}>
  <input name=dataIni type=hidden value={$dataIni}>
  <input name=dataFim type=hidden value={$dataFim}>
  <input name=parametro type=hidden value={$parametro}>

  <!-- page content -->
  <div class="" role="main">
    <!-- page content -->
    <div class="right_col" role="main">
      <!-- Indicadores -->
      <div class="row tile_count">
        <div class="col-md-3 col-sm-6 col-xs-6 tile_stats_count" name="divsContato">
          <span class="count_top"><i class="fa fa-user"></i> Contatos</span>
          <div class="count" name="valores"><center> {$contatoDiario} </center></div>
          <span class="count_bottom" title="Registros de contato de hoje"><i class="fa fa-calendar"></i> Hoje</span>
        </div>
        <div class="col-md-3 col-sm-6 col-xs-6 tile_stats_count" name="divsContato">
          <span class="count_top"><i class="fa fa-user"></i> Contatos</span>
          <div class="count" name="valores"><center> {$contatoPeriodo} </center></div>
          <span class="count_bottom" title="{$dataIni} a {$dataFim}"><i class="fa fa-calendar"></i> Período</span>
        </div>
        <div class="col-md-3 col-sm-6 col-xs-6 tile_stats_count" name="divsDiario">
          <span class="count_top"><i class="fa fa-shopping-basket"></i> Oportunidades</span>
          <div class="count" name="valores">{$oportunidadeDiario}</div>
          <span class="count_bottom" title="Hoje"><i class="fa fa-calendar"></i> Diário</span>
        </div>
        <div class="col-md-3 col-sm-6 col-xs-6 tile_stats_count" name="divsDiario">
          <span class="count_top"><i class="fa fa-shopping-basket"></i> Oportunidades</span>
          <div class="count" name="valores"><i class="red">{$oportunidadePeriodo}</i></div>
          <span class="count_bottom" title="{$dataIni} a {$dataFim}"><i class="fa fa-calendar"></i> Período</span>
        </div>
      </div>

      <!-- Filtros (painel recolhível) -->
      <div class="row">
        <div class="col-md-12">
          <div class="x_panel tile crm-dash-filtros-panel">
            <div class="x_title">
              <h2 style="color: #73879C; margin: 0;">
                <a class="crm-dash-filtros-toggle collapsed" role="button" data-toggle="collapse" href="#crmDashFiltrosCollapse"
                  aria-expanded="false" aria-controls="crmDashFiltrosCollapse" id="headingCrmDashFiltros">
                  <i class="fa fa-filter"></i> Filtros
                  <i class="fa fa-chevron-up pull-right crm-dash-filtros-chevron" aria-hidden="true"></i>
                </a>
              </h2>
              <div class="clearfix"></div>
            </div>
            <div id="crmDashFiltrosCollapse" class="collapse" aria-labelledby="headingCrmDashFiltros">
            <div class="x_content">
              <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 form-group">
                  <label class="crm-dash-filtro-label" for="dataConsulta">Período</label>
                  <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                    <input type="text" name="dataConsulta" id="dataConsulta" class="form-control"
                      value="{$dataIni} - {$dataFim}" placeholder="dd/mm/aaaa – dd/mm/aaaa">
                  </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 form-group">
                  <label class="crm-dash-filtro-label" for="centroCusto">Centro de custo</label>
                  <select class="select2_multiple form-control" multiple="multiple" id="centroCusto" name="centroCusto[]">
                    {html_options values=$centroCusto_ids output=$centroCusto_names selected=$centroCusto_id}
                  </select>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 form-group">
                  <label class="crm-dash-filtro-label" for="vendedor">Vendedor</label>
                  <select class="select2_multiple form-control" multiple="multiple" id="vendedor" name="vendedor">
                    {html_options values=$vendedor_ids output=$vendedor_names selected=$vendedor_id}
                  </select>
                </div>
              </div>
              <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 form-group">
                  <label class="crm-dash-filtro-label" for="classe">Situação do cliente</label>
                  <select class="select2_multiple form-control" multiple="multiple" id="classe" name="classe[]">
                    {html_options values=$classe_ids output=$classe_names selected=$classe_id}
                  </select>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 form-group">
                  <label class="crm-dash-filtro-label" for="estado">Estado (UF)</label>
                  <select class="select2_multiple form-control" multiple="multiple" id="estado" name="estado[]">
                    {html_options values=$estado_ids output=$estado_names selected=$estado_id}
                  </select>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 form-group">
                  <label class="crm-dash-filtro-label" for="cidade">Cidade</label>
                  <input type="text" name="cidade" id="cidade" class="form-control" value="{$cidade}" placeholder="Nome da cidade">
                </div>
              </div>
              <div class="row">
                <div class="col-xs-12 form-group crm-dash-filtros-acoes text-right">
                  <button type="button" class="btn" id="btnSubLet" onClick="javascript:submitLetra('');">
                    <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                    <span>Pesquisar</span>
                  </button>
                </div>
              </div>
            </div>
            </div>
          </div>
        </div>
      </div>

        <div class="row" id="divs">
          <div class="col-md-6 col-sm-6 ">
            <div class="x_panel tile" id="cot">
              <div class="x_title">
                <h2 style="color: #73879C;">Clientes ({$totalClientes})</h2>
                <div class="clearfix"></div>
                <div class="form-group" style="margin: 10px 0 0 0;">
                  <div class="input-group input-group-sm">
                    <span class="input-group-addon"><i class="fa fa-search"></i></span>
                    <input type="search" id="crmDashBuscaCliente" class="form-control" placeholder="Buscar na lista (nome, cidade, telefone, e-mail…)" autocomplete="off" />
                  </div>
                </div>
              </div>
              <h4></h4>
              <ul class="list-unstyled scroll-view table-striped" id="ulCliente">
                {if $resultClientes|@count neq 0}
                  {section name=i loop=$resultClientes}
                    <li class="media event crm-dash-cliente-item" id="{$resultClientes[i].CLIENTE}">
                      <a class="pull-left profile_thumb">
                        <i class="fa fa-user"></i>
                      </a>
                      <button type="button" id="iconesManutencaoCotacao" class="btn btn-primary btn-xs pull-right"
                        onclick="javascript:abrirNewTab('index.php?mod=ped&form=pedido_ps&submenu=abrirDashboardCrm&dashboard_origem=dashboard_crm&param={$resultClientes[i].CLIENTE}');">
                        <i class="fa fa-shopping-basket" style="margin-left: -4px !important;" aria-hidden="true"></i>
                      </button>
                      <button type="button" id="iconesManutencao" class="btn btn-primary btn-xs pull-right"
                        onclick="javascript:abrirNewTab('index.php?mod=crm&form=contas&submenu=alterar&dashboard_origem=dashboard_crm&param={$resultClientes[i].CLIENTE}');">
                        <span class="glyphicon glyphicon-pencil" style="margin-left: -4px;" aria-hidden="true"
                          data-toggle="tooltip" title="Editar"></span>
                      </button>
                      <div class="media-body">
                        <a class="title"
                          href="javascript:buscaAcompanhamentos({$resultClientes[i].CLIENTE})">
                          {$resultClientes[i].NOME} </a>
                        <p><small>{$resultClientes[i].CIDADE} - {$resultClientes[i].UF} </small></p>
                        <p> ({$resultClientes[i].FONEAREA}) {$resultClientes[i].FONE} | 
                      {if $resultClientes[i].EMAIL eq ''}
                            SEM E-MAIL 
                      {else} {$resultClientes[i].EMAIL} 
                      {/if}</p>
                      </div>
                    </li>

                    {/section}
                  {else}
                    <div class="clienteNaoLocalizado"><center>
                      <span class="" aria-hidden="true" data-toggle="tooltip" title="Clientes não localizados">
                        <i style="font-size: 40px;" class="fa fa-user-times" aria-hidden="true"></i>
                      </span>
                      <h3> Não foi localizado nenhum cliente </h3>
                    </center></div>
                  {/if}
              </ul>
            </div>
          </div>

{include file="crm_dashboard_lateral_acompanhamentos.tpl"}
        </div>
      </div>
    </div>

  {include file="template/database.inc"}
  </form>

{include file="crm_dashboard_modal_acompanhamento.tpl"}

<!-- Select2 -->
<script src="{$bootstrap}/jquery.inputmask/dist/jquery.inputmask.bundle.js"></script>
<script src="{$bootstrap}/select2-master/dist/js/select2.full.min.js"></script>
<script src="js/moment/moment.min.js"></script>
<script src="js/datepicker/daterangepicker.js"></script>
  <script type="text/javascript" src="{$pathSweet}/dist/sweetalert2.all.min.js"></script>
  <script type="text/javascript" src="{$pathJs}/crm/s_crm_dashboard.js"></script>

<script>
  $(document).ready(function() {
    $("#centroCusto.select2_multiple").select2({ allowClear: true, width: "100%" });
    $("#vendedor.select2_multiple").select2({ maximumSelectionLength: 1, allowClear: true, width: "100%" });
    $("#classe.select2_multiple").select2({ allowClear: true, width: "100%" });
    $("#estado.select2_multiple").select2({ allowClear: true, width: "100%" });
  });
</script>

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
        monthNames: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
        firstDay: 1
    }
  },
  function(start, end, label) {
    f = document.dashboardLancamento;
    if (f && f.dataIni && f.dataFim) {
      f.dataIni.value = start.format('DD/MM/YYYY');
      f.dataFim.value = end.format('DD/MM/YYYY');
    }
});
</script>

  


