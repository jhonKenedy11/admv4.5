<style>
  @media (min-width:768px) {
    [name="valores"] {
      font-size: 40px !important;
    }

    [name="progressMeta"] {
      height: 20px !important;
      width: 140px !important;
    }

    [name="divsPed"] {
      width: 16%;
      padding: 0%;
      color: #73879C;
      font-family: "Helvetica Neue", Roboto, Arial, "Droid Sans", sans-serif;
    }

    [name="divsPedValor"] {
      width: 195px !important;
      padding-right: 0;
      color: #73879C;
      font-family: "Helvetica Neue", Roboto, Arial, "Droid Sans", sans-serif;
      padding: 0 10px 0 20px;
    }

    [name="divsPedValor"]:before {
      content: "" !important;
      position: absolute !important;
      left: 0 !important;
      height: 65px !important;
      border-left: 2px solid #ADB2B5;
      margin-top: 10px !important;
    }

    [name="divMeta"] {
      width: 30%;
    }

    [name="valoresTotal"] {
      margin-top: -5px;
      font-weight: bold;
      font-size: 37px !important;
    }

    [name="prog"] {
      width: 185px;
    }

    .vlr3 {
      font-size: 15px !important;
      color: #73879C;
      font-family: "Helvetica Neue", Roboto, Arial, "Droid Sans", sans-serif;
    }

    .vlrMeta {
      font-size: 17px !important;
      color: #73879C;
      font-style: italic;
      font-family: "Helvetica Neue", Roboto, Arial, "Droid Sans", sans-serif;
    }

    .count_top {
      font-size: 18px !important;
    }

    .count_bottom {
      font-size: 14px !important;
      font-family: "Helvetica Neue", Roboto, Arial, "Droid Sans", sans-serif;
    }

    .sr-only {
      margin-left: 20px !important;
      font-size: 10px;
    }

    #columnchart_values {
      position: static;
      width: 400px;
      height: 200px;
    }
  }

  .select2-selection--multiple {
    border-radius: 8px !important;
  }

  li.select2-selection__choice {
    border-radius: 5px !important;
  }

  li.select2-results__option--highlighted {
    border-radius: 8px !important;
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

  #iconesManutCot {
    width: 25px !important;
    align-items: center;
    margin-top: 19px;
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
</style>
<script type="text/javascript" src="{$pathJs}/crm/s_dashboard.js"> </script>
<script type="text/javascript" src="{$pathSweet}/dist/sweetalert2.all.min.js"></script>

<form id="lancamento" name="lancamento" data-parsley-validate METHOD="POST" class="form-horizontal form-label-left"
  ACTION={$SCRIPT_NAME}>
  <input name=mod type=hidden value="crm">
  <input name=form type=hidden value="crm_dashboard">
  <input name=id type=hidden value="">
  <input name=idAcomp type=hidden value={$idAcomp}>
  <input name=opcao type=hidden value={$opcao}>
  <input name=letra type=hidden value={$letra}>
  <input name=submenu type=hidden value={$subMenu}>
  <input name=dataIni type=hidden value={$dataIni}>
  <input name=dataFim type=hidden value={$dataFim}>
  <input name=motivoSelected type=hidden value={$motivoSelected}>
  <input name=idVendaPerdida type=hidden value={$idVendaPerdida}>
  <input name=verSomenteInfoDaLoja type=hidden value={$verSomenteInfoDaLoja}>
  <input name=vertodoslancamentos type=hidden value={$vertodoslancamentos}>

  <!-- page content -->
  <div class="" role="main">
    <!-- page content -->
    <div class="right_col" role="main">
      <!-- top tiles -->
      <div class="row col-md-12 col-sm-12" style="display: inline-block;">
        <div class="tile_count">
          <div class="col-md-1 col-sm-4  tile_stats_count" name="divsPed">
            <span class="count_top"><i class="fa fa-user"></i> Cotações </span>
            <div class="count" name="valores">{$cotOntem}</div>
            <span class="count_bottom" title="Ontem"><i class=""><i class="fa fa-calendar"> D-1 </i></i></span>
          </div>
          <div class="col-md-1 col-sm-4  tile_stats_count" name="divsPed">
            <span class="count_top"><i class="fa fa-clock-o"></i> Cotações </span>
            <div class="count" name="valores">{$cotHoje}</div>
            <span class="count_bottom" title="Hoje"><i class=""><i class="fa fa-calendar"> D+0 </i></i></span>
          </div>
          <div class="col-md-1 col-sm-4  tile_stats_count" name="divsPed">
            <span class="count_top"><i class="fa fa-user"></i> Conversão </span>
            <div class="count" name="valores">{$conversao}</div>
            <span class="count_bottom" title="Hoje"><i class=""><i class="fa fa-calendar"> D+0 </i></i></span>
          </div>
          <div class="col-md-1 col-sm-4  tile_stats_count" name="divsPed">
            <span class="count_top"><i class="fa fa-user"></i> Perdidas </span>
            <div class="count" name="valores"><i class="red">{$perdidos}</i></div>
            <span class="count_bottom"><i class="fa fa-calendar"> Per&iacute;odo </i></span>
          </div>
          <!--<div class="col-md-1 col-sm-4  tile_stats_count" name="divsPed">
          <span class="count_top"><i class="fa fa-user"></i> Pedidos </span>
          <div class="count" name="valores">
            <span class="count_bottom">
              {if $iconeFaSortPed eq 'desc'}
                <i class="red" id="iconePercMeta">
                  <i class="fa fa-sort-desc"></i>
              {else}
                <i class="green" id="iconePercMeta">
                  <i class="fa fa-sort-asc"></i>
              {/if}
                <b> {$percPeds} %</b>
              </i>
            </span>
          </div>
        </div>-->

          <div class="col-md-2 col-sm-4 tile_stats_count" name="divsPed">
            <span class="count_top"><i class="fa fa-user"></i> Pedidos </span>
            <div class="count" name="valores"> {$pedMes} </div>
            <div class="col-md-12 col-sm-12">
              <div id="percMeta" class="col-md-6 col-sm-6 pull-left">
                <span class="count_bottom">
                  <i class="green" id="iconePercMeta">
                    <b> {$percPed} %</b>
                  </i>
                </span>
              </div>
              <div class="col-md-6 col-sm-6">
                <b class="qtdTotal" id="metaMensal">Total:{$totalPedMes}</b>
              </div>
            </div>
          </div>

          <div class="col-md-2 col-sm-4 tile_stats_count_total" name="divsPedValor">
            <span class="count_top"><i class="fa fa-user"></i> Pedidos per&iacute;odo</span>
            <div class="count" name="valoresTotal"><i class="green"> {$pedMesValor|number_format:2:",":"."}</i></div>
            <div class="col-md-12 col-sm-12" id="vlres">
              <div id="percMeta" class="col-md-6 col-sm-6 pull-left">
                <span class="count_bottom" title="{$vlrMetaMensal}">
                  {if $iconeFaSort eq 'desc'}
                    <i class="red" id="iconePercMeta">
                      <i class="fa fa-sort-desc"></i>
                    {else}
                      <i class="green" id="iconePercMeta">
                        <i class="fa fa-sort-asc"></i>
                      {/if}
                      <b> {$percMetaMensal} %</b>
                    </i>
                </span>
              </div>
              <div class="col-md-6 col-sm-6">
                <b class="" id="metaMensal">Meta:{$vlrMetaMensal|number_format:2:",":"."}</b>
              </div>
            </div>
          </div>

        </div>
      </div>

      <!--COMBOS PESQUISAS -->
      <div class="row col-md-12 col-sm-12">

        <div class="form-group col-md-3 col-sm-12 col-xs-12">
          <label class="" id="labelPesq">Per&iacute;odo</label>
          <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
          <div>
            <input type="text" name="dataConsulta" id="dataConsulta" class="form-control"
              value="{$dataIni} - {$dataFim}">
          </div>
        </div>

        <div class="form-group col-md-4 col-sm-4 col-xs-4">
          <label for="centroCusto" id="labelPesq">Centro de Custo</label>
          <SELECT {if ($verSomenteInfoDaLoja == false)} enable {else} disabled {/if}
            class="select2_multiple form-control" multiple="multiple" id="centroCusto" name="centroCusto">
            {html_options values=$centroCusto_ids output=$centroCusto_names selected=$centroCusto_id}
          </SELECT>
        </div>

        <div class="form-group col-md-4 col-sm-12 col-xs-12">
          <label for="vendedor" id="labelPesq">Vendedor</label>
          <SELECT {if ($vertodoslancamentos == true )} enable {else} disabled {/if}
            class="select2_multiple form-control" multiple="multiple" id="vendedor" name="vendedor">
            {html_options values=$vendedor_ids output=$vendedor_names selected=$vendedor_id}
          </SELECT>
        </div>

        <div class="form-group col-md-1 col-sm-12 col-xs-12">
          <label>&nbsp;</label>
          <p>
            <button type="button" class="btn" id="btnSubLet" onClick="javascript:submitLetra('');">
              <span class="glyphicon glyphicon-search" id="btnSubLetPes aria-hidden=" true"></span><span>
                Pesquisa</span>
            </button>
        </div>

      </div>
      <!--FIM COMBOS PESQUISAS -->

      <div class="row" id="divs">
        <div class="col-md-6 col-sm-6 ">
          <div class="x_panel tile" id="cot">
            <div class="x_title">
              <h2 style="color: #73879C;">Cotações</h2>
              <button type="button" class="btn btn-success btn-xs pull-right" id="btnAddCot"
                onclick="javascript:abrirNewTab(
                    'index.php?mod=ped&form=pedido_venda_telhas&submenu=cadastrar&opcao=imprimir&dashboard_origem=dashboard_crm');">
                <span class="glyphicon fa fa-plus-circle" aria-hidden="true" data-toggle="tooltip"
                  title="Adicionar Cotação"></span>
              </button>
              <div class="clearfix"></div>
            </div>
            <h4></h4>
            <ul class="list-unstyled scroll-view table-striped" id="ulCotacao">
              {section name=i loop=$resultCot}
              <li class="media event" id="{$resultCot[i].PEDIDO}">
                <a class="pull-left profile_thumb">
                  <i class="fa fa-user"></i>
                </a>
                <button type="button" id="iconesManutCot" class="btn btn-danger btn-xs pull-right" data-toggle="modal"
                  data-target="#modalVendaPerdida" onclick="vendaPerdida({$resultCot[i].ID})">
                  <span span class="glyphicon glyphicon-alert" style="margin-left: -4px;" aria-hidden="true"
                    data-toggle="tooltip" title="Venda Perdida"></span>
                </button>
                <button type="button" id="iconesManutCot" class="btn btn-primary btn-xs pull-right"
                  onclick="javascript:abrirNewTab('index.php?mod=ped&form=pedido_venda_telhas&submenu=alterar&opcao=imprimir&dashboard_origem=dashboard_crm&id={$resultCot[i].ID}&pessoa={$resultCot[i].CLIENTE}&situacaoCombo={$resultCot[i].SITUACAO}');">
                  <span class="glyphicon glyphicon-pencil" style="margin-left: -4px;" aria-hidden="true"
                    data-toggle="tooltip" title="Editar"></span>
                </button>
                <div class="media-body">
                  <a class="title"
                    href="javascript:buscaAcompanhamentos({$resultCot[i].ID}, {$resultCot[i].CLIENTE}, '{$resultCot[i].NOMEREDUZIDO}')">{$resultCot[i].PEDIDO}
                    - {$resultCot[i].NOME} </a>
                  <p><strong>R$ {$resultCot[i].TOTAL|number_format:2:",":"."} </strong> {$resultCot[i].CIDADE} -
                    {$resultCot[i].UF} </p>
                  <p> <small>Emissão: {$resultCot[i].EMISSAO|date_format:"%e %b, %Y"}</small> - <small>C.C.:
                      {$resultCot[i].DESCCUSTO}</small>
                  </p>
                </div>
              </li>
              {/section}
            </ul>
          </div>
        </div>

        <div class="col-md-6 col-sm-6 ">
          <div class="x_panel tile" id="acomp">
            <div class="x_title">
              <h2 style="color: #73879C;">Acompanhamentos</h2>
              <button type="button" class="btn btn-success btn-xs pull-right" id="calendar"
                onclick="javascript:visualizarCalendario('');">
                <span class="glyphicon glyphicon-calendar calendar" aria-hidden="true" data-toggle="tooltip"
                  title="Visualizar Calendário"></span>
              </button>
              <button type="button" class="btn btn-success btn-xs pull-right" id="btnAddAcomp"
                onclick="javascript:abrirAcompanhamento('');">
                <span class="glyphicon fa fa-plus-circle" aria-hidden="true" data-toggle="tooltip"
                  title="Adicionar Acompanhamento"></span>
              </button>
              <div class="clearfix"></div>
            </div>
            <h4> </h4>
            <ul class="list-unstyled scroll-view" id="ulAcompanhamento">
              <input name=idCotacao type=hidden value={$idCotacao}>
              <input name=idCliente type=hidden value={$idCliente}>
              <input name=nomeCliente type=hidden value={$nomeCliente}>
              {section name=i loop=$resultAcomp}
              <input name=tempClienteOtimizaIcone type=hidden value={$tempClienteOtimizaIcone}>
              <li class="media event table-striped">
                <a class="pull-left border-aero profile_thumb">
                  <i class="fa fa-user aero"></i>
                </a>
                <div class="media-body">
                  <button type="button" id="iconesManutCot" class="btn btn-primary btn-xs pull-right"
                    onclick="javascript:editarAcompanhamento({$resultAcomp[i].ID});">
                    <span class="glyphicon glyphicon-pencil" style="margin-left: -4px;" aria-hidden="true"
                      data-toggle="tooltip" title="Editar"></span>
                  </button>
                  <a class="title">{$resultAcomp[i].PEDIDO_ID} - {$resultAcomp[i].NOMEREDUZIDO} </a>
                  <p>{$resultAcomp[i].DATA|date_format:"%e %b, %Y - %H:%M:%S"} </p>
                  <p><strong>{$resultAcomp[i].RESULTADO} </strong> </p>
                  <p> Ligar: {$resultAcomp[i].LIGARDIA|date_format:"%e %b, %Y - %H:%M:%S"}
                  </p>
                </div>
              </li>
              {/section}

            </ul>
          </div>
        </div>
      </div>
    </div>
    <!-- Modal Venda perdida -->
    <div class="modal fade" id="modalVendaPerdida" role="dialog">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-body">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <input hidden type="text" name="cotacao" id="cotacao" value="{$cotacao}">
              <label>Motivo</label>
              <div class="panel panel-default small">
                <select name="motivoPerdido" id="motivoPerdido" class="form-control">
                  {html_options values=$motivo_ids output=$motivo_names selected=$motivo_id}
                </select>
              </div>
            </div>

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary" data-dismiss="modal"
              onClick="javascript:salvarMotivoNoPedido(cotacao.value);">Salvar</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
          </div>
        </div>
      </div>
    </div>
    <!--FIM Modal Venda perdida -->

  </div>
  {include file="template/database.inc"}
</form>

<!-- bootstrap-progressbar -->
<script src="{$bootstrap}/bootstrap-progressbar/bootstrap-progressbar.min.js"></script>
<!-- Select2 -->
<script src="{$bootstrap}/select2-master/dist/js/select2.full.min.js"></script>
<!-- bootstrap-daterangepicker -->
<script src="js/moment/moment.min.js"></script>
<script src="js/datepicker/daterangepicker.js"></script>

<script>
  $(document).ready(function() {
    $("#centroCusto.select2_multiple").select2({
      allowClear: true,
      width: "95%"
    });

  });
</script>
<script>
  $(document).ready(function() {
    $("#vendedor.select2_multiple").select2({
      maximumSelectionLength: 1,
      allowClear: true,
      width: "95%"
    });

  });
</script>
<!-- daterangepicker -->
<script type="text/javascript">
  $('input[name="dataConsulta"]').daterangepicker({
      startDate: moment("{$dataIni}", "DD/MM/YYYY"),
      endDate: moment("{$dataFim}", "DD/MM/YYYY"),
      calender_style: "picker_2",
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
        monthNames: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro',
          'Outubro', 'Novembro', 'Dezembro'
        ],
        firstDay: 1
      }

    },
    //funcao para recuperar o valor digitado        
    function(start, end, label) {
      f = document.lancamento;
      f.dataIni.value = start.format('DD/MM/YYYY');
      f.dataFim.value = end.format('DD/MM/YYYY');
    });
</script>
<script>
  function vendaPerdida(cotacao) {
    var cotacao = cotacao;
    $("#cotacao").val(cotacao);
  }
</script>