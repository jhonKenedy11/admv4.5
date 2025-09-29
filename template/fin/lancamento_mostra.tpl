<style>
  .form-control,
  .x_panel {
    border-radius: 5px;
  }
</style>
<script type="text/javascript" src="{$pathJs}/fin/s_lancamento.js"> </script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<!-- page content -->
<div class="right_col" role="main">

  <div class="">

    <div class="row">

      <!-- panel principal  -->
      <div class="col-md-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2>Lan&ccedil;amentos Financeiros - Consulta</h2>
            {include file="../bib/msg.tpl"}
            <ul class="nav navbar-right panel_toolbox">
              {if $consulta eq "C"}
                <li><button type="button" class="btn btn-warning" onClick="javascript:submitLetra();">
                    <span class="glyphicon glyphicon-zoom-in" aria-hidden="true"></span><span> Pesquisa</span>
                  </button>
                </li>
              {/if}
              <li><button type="button" class="btn btn-primary" onClick="javascript:submitCadastro('');">
                  <span class="glyphicon glyphicon-plus" aria-hidden="true"></span><span> Cadastro</span>
                </button>
              </li>
              <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
              <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i
                    class="fa fa-wrench"></i></a>
                <ul class="dropdown-menu" role="menu">
                  <li>
                    <button type="button" class="btn btn-dark btn-xs" onClick="javascript:consultaLctoData();"><span>
                        Lançamentos por Data</span></button>
                  </li>
                  <li>
                    <button type="button" class="btn btn-dark btn-xs" onClick="javascript:consultaFluxoCaixa();"><span>
                        Fluxo de Caixa</span></button>
                  </li>
                  <li>
                    <button type="button" class="btn btn-dark btn-xs"
                      onClick="javascript:consultaConsolidacao();"><span> Consolida&ccedil;&atilde;o</span></button>
                  </li>
                  <li>
                    <button type="button" class="btn btn-dark btn-xs" onClick="javascript:consultaGenero();"><span>
                        Resumo Genero</span></button>
                  </li>
                  <li>
                    <button type="button" class="btn btn-dark btn-xs" onClick="javascript:consultaCentroCusto();"><span>
                        Rel. Centro de Custo Analítico</span></button>
                  </li>
                  <li>
                    <button type="button" class="btn btn-dark btn-xs"
                      onClick="javascript:consultaCentroCusto('hidden');"><span> Rel. Centro de Custo
                        Sintético</span></button>
                  </li>
                  <li>
                    <button type="button" class="btn btn-dark btn-xs" onClick="javascript:consultaDRE();"><span> DRE
                        Financeiro</span></button>
                  </li>
                  <li>
                    <button type="button" class="btn btn-dark btn-xs" onClick="javascript:lancPedDataEntrega();"><span>
                        Rel. Financeiro Data Entrega</span></button>
                  </li>
                  <li><button type="button" class="btn btn-dark btn-xs" onClick="javascript:agrupaLancModal();">
                      <span class="glyphicon glyphicon-plus" aria-hidden="true"></span><span> Agrupar Lançamentos</span>
                    </button>
                  </li>
                  <li><button type="button" class="btn btn-dark btn-xs" onClick="javascript:baixaLoteLancModal();">
                      <span class="glyphicon glyphicon-plus" aria-hidden="true"></span><span> Baixa em Lote</span>
                    </button>
                  </li>
                  <li><button type="button" class="btn btn-dark btn-xs" onClick="javascript:impSlipLote();">
                      <span class="glyphicon glyphicon-print" aria-hidden="true"></span><span> Slip em Lote</span>
                    </button>
                  </li>
                </ul>

              </li>
              <li><a class="close-link"><i class="fa fa-close"></i></a>
              </li>
            </ul>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <form id="lancamento" name="lancamento" data-parsley-validate METHOD="POST"
              class="form-horizontal form-label-left" ACTION={$SCRIPT_NAME}>

              <!--form class="full" NAME="lancamento" METHOD="POST" class="form-horizontal form-label-left" novalidate ACTION={$SCRIPT_NAME} -->
              <input name=mod type=hidden value="{$mod}">
              <input name=form type=hidden value="{$form}">
              <input name=id type=hidden value="">
              <input name=letra type=hidden value={$letra}>
              <input name=submenu type=hidden value={$subMenu}>
              <input name=opcao type=hidden value="">
              <input name=fornecedor type=hidden value="">
              <input name=pessoa type=hidden value={$pessoa}>
              <input name=genero type=hidden value={$genero}>
              <input name=dataIni type=hidden value={$dataIni}>
              <input name=dataFim type=hidden value={$dataFim}>
              <input name=linhas type=hidden value={$linhas}>
              <input name=vencimento type=hidden value={$vencimento}>
              <input name=total type=hidden value={$total}>
              <input name=dadosLancAgrupamento type=hidden value={$dadosLancAgrupamento}>
              <input name=dadosLanc type=hidden value={$dadosLanc}>
              <input name=mOriginal type=hidden value={$mOriginal}>

              <div class="form-group col-md-2 col-sm-12 col-xs-12">
                <label>Data Refer&ecirc;ncia</label>
                <select class="form-control" name=dataReferencia id="dataReferencia">
                  {html_options values=$datas_ids selected=$datas_id output=$datas_names}
                </select>
              </div>

              <div class="form-group col-md-4 col-sm-12 col-xs-12">
                <label class="">Per&iacute;odo</label>
                <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                <div>
                  <input type="text" name="dataConsulta" id="dataConsulta" class="form-control"
                    value="{$dataIni} - {$dataFim}">
                </div>
              </div>

              <!--fieldset>
                                  <div class="control-group">
                                    <div class="controls">
                                      <div class="input-prepend input-group">
                                        <span class="add-on input-group-addon"><i class="glyphicon glyphicon-calendar fa fa-calendar"></i></span>
                                        <input type="text" style="width: 200px" name="reservation" id="reservation" class="form-control" value="03/18/2013 - 03/23/2013" />
                                      </div>
                                    </div>
                                  </div>
                                </fieldset-->


              <div class="form-group col-md-3 col-sm-12 col-xs-12">
                <label>Tipo Lan&ccedil;amento</label>
                <select class="select2_multiple form-control" multiple="multiple" id="tipolanc" name="tipolanc">
                  {html_options values=$tipoLanc_ids selected=$tipoLanc_id output=$tipoLanc_names}
                </select>
              </div>

              <div class="form-group col-md-3 col-sm-12 col-xs-12">
                <label>Situa&ccedil;&atilde;o Lan&ccedil;amento</label>
                <select class="select2_multiple form-control" multiple="multiple" id="sitlanc" name="sitlanc">
                  {html_options values=$situacaoLanc_ids selected=$situacaoLanc_id output=$situacaoLanc_names}
                </select>
              </div>

              <div class="clearfix"></div>

              <div class="form-group col-md-3 col-sm-12 col-xs-12">
                <select class="select2_multiple form-control" multiple="multiple" id="sitdocto" name="sitdocto">
                  {html_options values=$situacaoDocto_ids selected=$situacaoDocto_id output=$situacaoDocto_names}
                </select>
              </div>

              <div class="form-group col-md-3 col-sm-12 col-xs-12">
                <select class="select2_multiple form-control" multiple="multiple" id="tipoDocto" name="tipoDocto">
                  {html_options values=$tipoDocumento_ids selected=$tipoDocumento_id output=$tipoDocumento_names}
                </select>
              </div>

              <div class="form-group col-md-3 col-sm-12 col-xs-12">
                <select class="select2_multiple form-control" multiple="multiple" id="conta" name="conta">
                  {html_options values=$conta_ids selected=$conta_id output=$conta_names}
                </select>
              </div>

              <div class="form-group col-md-3 col-sm-12 col-xs-12">
                <select class="select2_multiple form-control" multiple="multiple" id="filial" name="filial">
                  {html_options values=$filial_ids selected=$filial_id output=$filial_names}
                </select>
              </div>

              <div class="clearfix"></div>
              <div class="form-group">
                <div class="col-md-4 col-sm-12 col-xs-12">
                  <div class="input-group">
                    <input type="text" class="form-control" id="descgenero" name="descgenero" placeholder="Genero"
                      value="{$descGenero}">
                    <span class="input-group-btn">
                      <button type="button" class="btn btn-primary"
                        onClick="javascript:abrir('{$pathCliente}/index.php?mod=fin&form=genero&opcao=pesquisar');">
                        <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                      </button>
                    </span>
                  </div>
                </div>

                <div class="col-md-6 col-sm-12 col-xs-12">
                  <div class="input-group">
                    <input type="text" class="form-control" id="nome" name="nome" placeholder="Conta" value="{$nome}">
                    <span class="input-group-btn">
                      <button type="button" class="btn btn-primary"
                        onClick="javascript:abrir('{$pathCliente}/index.php?mod=crm&form=contas&opcao=pesquisar');">
                        <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                      </button>
                    </span>
                  </div>
                </div>
              </div>

              <!-- include modal -->
              {include file="lancamento_agrupamento_modal.tpl"}
              {include file="lancamento_baixa_lote_modal.tpl"}

            </form>


          </div>

        </div> <!-- div class="x_content" = inicio tabela -->
      </div> <!-- div class="x_panel" = painel principal-->



      <!-- panel tabela dados -->
      <div class="col-md-12 col-xs-12">
        <div class="x_panel small">
          <!--table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap table-condensed" cellspacing="0" width="100%"-->
          <table id="datatable-buttons" class="table table-bordered jambo_table">
            <thead>
              <tr class="headings">
                <th>#</th>
                <th>Pessoa</th>
                <th>Docto</th>
                <th>Situa&ccedil;&atilde;o</th>
                <th>Centro Custo</th>
                <th>Genero</th>
                <th>Emissão</th>
                <th>Vencimento</th>
                <th>Movimento</th>
                <th>Total</th>
                <th class="invis">pessoaId</th>
                <th style="width: 110px;">Manuten&ccedil;&atilde;o</th>
              </tr>
            </thead>

            <tbody>

              {section name=i loop=$lanc}
                {assign var="total" value=$total+1}
                {if $lanc[i].TIPOLANCAMENTO eq "PAGAMENTO"}
                  <tr class="even pointer danger">
                    {assign var="pagamentoTotal" value=$pagamentoTotal+$lanc[i].TOTAL}
                  {else}
                  <tr class="even pointer info">
                    {assign var="recebimentoTotal" value=$recebimentoTotal+$lanc[i].TOTAL}
                  {/if}
                  <td> <input type="checkBox" name="lancChecked" id="{$lanc[i].ID}" /> </td>
                  <td> {$lanc[i].NOME} </td>
                  <td> {$lanc[i].DOCTO}-{$lanc[i].SERIE}-{$lanc[i].PARCELA} / {$lanc[i].ORIGEM}-{$lanc[i].NUMLCTO}</td>
                  <td> {$lanc[i].SITUACAOPGTO} </td>
                  <td> {$lanc[i].FILIAL} </td>
                  <td> {$lanc[i].DESCGENERO} </td>
                  <td> {$lanc[i].EMISSAO|date_format:"%e %b, %Y"} </td>
                  <td> {$lanc[i].VENCIMENTO|date_format:"%e %b, %Y"} </td>
                  <td> {$lanc[i].PAGAMENTO|date_format:"%e %b, %Y"} </td>
                  <td>{$lanc[i].TOTAL|number_format:2:",":"."} </td>
                  <td class="invis">{$lanc[i].PESSOAID} |
                    {$lanc[i].TIPOLANCAMENTO}|{$lanc[i].MULTA|number_format:2:",":"."} |
                    {$lanc[i].JUROS|number_format:2:",":"."} | {$lanc[i].DESCONTO|number_format:2:",":"."} |
                    {$lanc[i].ORIGINAL|number_format:2:",":"."} </td>
                  <td class=" last">
                    <button type="button" class="btn btn-primary btn-xs"
                      onclick="javascript:submitAlterar('{$lanc[i].ID}');"><span class="glyphicon glyphicon-pencil"
                        aria-hidden="true" title="Editar"></span></button>
                    <button type="button" class="btn btn-warning btn-xs"
                      onclick="javascript:abrir('{$pathCliente}/index.php?mod=blt&form=boleto_imprime&opcao=blank&letra={$lanc[i].ID}');">
                      <span class="glyphicon glyphicon-barcode" aria-hidden="true" title="Boleto"></span></button>
                    <button type="button" class="btn btn-info btn-xs"
                      onclick="javascript:abrir('{$pathCliente}/index.php?mod=fin&form=rel_recibo_imprime&opcao=imprimir&id={$lanc[i].ID}');">
                      <span class="glyphicon glyphicon-barcode" aria-hidden="true" title="Recibo"></span></button>
                    <button type="button" class="btn btn-success btn-xs"
                      onclick="javascript:abrir('{$pathCliente}/index.php?mod=fin&form=rel_duplicata_imprime&opcao=imprimir&id={$lanc[i].ID}');">
                      <span class="glyphicon glyphicon-barcode" aria-hidden="true" title="Duplicata"></span></button>
                    <button type="button" class="btn btn-info btn-xs"
                      onclick="javascript:abrir('{$pathCliente}/index.php?mod=fin&form=rel_slip_imprime&opcao=imprimir&id={$lanc[i].ID}');">
                      <span class="glyphicon glyphicon-print" aria-hidden="true" title="Slip"></span></button>
                  </td>
                </tr>

              {/section}

            </tbody>

          </table>





        </div> <!-- div class="x_content" = inicio tabela -->
      </div> <!-- div class="x_panel" = painel principal-->

    </div> <!-- div class="col-md-12 col-sm-12 col-xs-12 "-->
  </div> <!-- div class="row "-->
</div> <!-- class='' = controla menu user -->



{include file="template/database.inc"}

<!-- Datatables -->
<style>
  .invis {
    display: none;
  }
</style>



<!-- bootstrap-daterangepicker -->
<script src="js/moment/moment.min.js"></script>
<script src="js/datepicker/daterangepicker.js"></script>

<!-- Select2 -->
<script src="{$bootstrap}/select2-master/dist/js/select2.full.min.js"></script>

<!-- Select2 -->
<script>
  $(document).ready(function() {
    $(".select2_single").select2({
      placeholder: "Selecione o Gênero",
      allowClear: true
    });

    $("#tipolanc.select2_multiple").select2({
      placeholder: "Escolha o Tipo Lançamento",
      allowClear: true
    });
    $("#sitlanc.select2_multiple").select2({
      placeholder: "Escolha a Situação Documento",
      allowClear: true
    });
    $("#tipoDocto.select2_multiple").select2({
      placeholder: "Escolha o Tipo Documento",
      allowClear: true
    });
    $("#sitdocto.select2_multiple").select2({
      //maximumSelectionLength: 4,
      placeholder: "Escolha a Situação Documento",
      allowClear: true
    });
    $("#tipoDocto.select2_multiple").select2({
      placeholder: "Escolha o Tipo Documento",
      allowClear: true
    });
    $("#conta.select2_multiple").select2({
      placeholder: "Escolha a Conta",
      allowClear: true
    });
    $("#filial.select2_multiple").select2({
      placeholder: "Escolha a filial",
      allowClear: true
    });

    $("#contaCombo.select2_multiple").select2({
      placeholder: "Escolha a Conta",
      allowClear: true
    });

  });
</script>
<!-- /Select2 -->

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
        monthNames: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro',
          'Outubro', 'Novembro', 'Dezembro'
        ],
        firstDay: 1
      }

    },
    //funcao para recuperar o valor digirado        
    function(start, end, label) {
      f = document.lancamento;
      f.dataIni.value = start.format('DD/MM/YYYY');
      f.dataFim.value = end.format('DD/MM/YYYY');
    });
</script>
<!-- /daterangepicker -->
<script>
  document.addEventListener("keypress", function(e) {
    if (e.keyCode === 13) {
      submitLetra();
    }
  });
</script>