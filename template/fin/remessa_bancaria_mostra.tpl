<style>
  .form-control,
  .x_panel {
    border-radius: 5px;
  }
</style>
<script type="text/javascript" src="{$pathSweet}/dist/sweetalert2.all.min.js"></script>
<script type="text/javascript" src="{$pathJs}/fin/s_fin.js"> </script>
<!-- page content -->
<div class="right_col" role="main">

  <div class="">

    <div class="row">

      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2>Remessa Bancária - Cobrança
              <strong>
                {if $nomeArq neq ''}
                  <a href="{$arquivo}" download>">Download Remessa - {$nomeArq}</a>

                  {/if}
                </strong>
              </h2>
              <ul class="nav navbar-right panel_toolbox">
                <li><button type="button" class="btn btn-warning" onClick="javascript:submitLetraRemessa();">
                    <span class="glyphicon glyphicon-zoom-in" aria-hidden="true"></span><span> Pesquisa</span>
                  </button>
                </li>
                <li><button type="button" class="btn btn-primary" onClick="javascript:submitConfirmaRemessa();">
                    <span class="glyphicon glyphicon-check" aria-hidden="true"></span><span> Confirma</span></button></li>
                {* <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                          <li class="dropdown">
                          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                          </li>
                          <li><a class="close-link"><i class="fa fa-close"></i></a>
                          </li> *}
              </ul>
              <div class="clearfix"></div>
            </div>
            <div class="x_content">
              <form id="remessa" name="remessa" data-parsley-validate METHOD="POST"
                class="form-horizontal form-label-left" ACTION={$SCRIPT_NAME}>
                <input name=mod type=hidden value="fin">
                <input name=form type=hidden value="remessa_bancaria">
                <input name=letra type=hidden value={$letra}>
                <input name=banco type=hidden value={$banco}>
                <input name=submenu type=hidden value={$subMenu}>
                <input name=lanc type=hidden value={$lanc}>
                <input name=dataIni type=hidden value="{$dataIni}">
                <input name=dataFim type=hidden value="{$dataFim}">



                <div class="form-group col-md-2 col-sm-4 col-xs-4">
                  <label class="">Per&iacute;odo Emissão</label>
                  <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                </div>
                <div class="form-group col-md-3 col-sm-8 col-xs-8">
                  <div>
                    <input type="text" name="dataConsulta" id="dataConsulta" class="form-control"
                      value="{$dataIni} - {$dataFim}">
                  </div>
                </div>

                <div class="form-group col-md-3 col-sm-12 col-xs-12">
                  <select class="select2_multiple form-control" multiple="multiple" id="conta" name="contaBanco">

                  {html_options values=$conta_ids selected=$conta_id output=$conta_names}
                  </select>
                </div>

                <div class="form-group col-md-3 col-sm-12 col-xs-12">
                  <select class="select2_multiple form-control" multiple="multiple" id="filial" name="filial">

                  {html_options values=$filial_ids selected=$filial_id output=$filial_names}
                  </select>
                </div>
              </form>
            </div>

            <div class="x_content">
              <!--table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap table-condensed" cellspacing="0" width="100%"-->
              <table id="datatable-buttons" class="table table-bordered jambo_table">
                <thead>
                  <tr class="headings">
                    <th>Num.Reg.</th>
                    <th>Pessoa</th>
                    <th>Docto</th>
                    <th>Situa&ccedil;&atilde;o</th>
                    <th>Genero</th>
                    <th>Vencimento</th>
                    <th>Total</th>
                  </tr>
                </thead>

                <tbody>


                  {section name=i loop=$lanc}

                    {assign var="total" value=$total+1}
                    <tr class="even pointer info">

                    {assign var="recebimentoTotal" value=$recebimentoTotal+$lanc[i].TOTAL}

                    {assign var="numReg" value=$numReg+1}

                      <td> {$numReg} </td>
                      <td> {$lanc[i].NOMEREDUZIDO} </td>
                      <td> {$lanc[i].DOCTO}-{$lanc[i].SERIE}-{$lanc[i].PARCELA} </td>
                      <td> {$lanc[i].SITUACAOPGTO} </td>
                      <td> {$lanc[i].DESCGENERO} </td>
                      <td> {$lanc[i].VENCIMENTO|date_format:"%e %b, %Y"} </td>
                      <td align=right>{$lanc[i].TOTAL|number_format:2:",":"."} </td>
                    </tr>
                    <p>

                  {/section}
                    <tr class="even pointer danger">

                      <td> </td>
                      <td> T O T A L R E M E S S A - Num. Reg:{$numReg}</td>
                      <td> </td>
                      <td> </td>
                      <td> </td>
                      <td> </td>
                      <td align=right>{$recebimentoTotal|number_format:2:",":"."} </td>
                    </tr>

                </tbody>

              </table>

            </div> <!-- div class="x_content" = inicio tabela -->
          </div> <!-- div class="x_panel" = painel principal-->
        </div> <!-- div class="col-md-12 col-sm-12 col-xs-12 "-->
      </div> <!-- div class="row "-->
    </div> <!-- class='' = controla menu user -->



                  {include file="template/database.inc"}

    <!-- /Datatables -->
    <!-- bootstrap-daterangepicker -->
    <script src="js/moment/moment.min.js"></script>
    <script src="js/datepicker/daterangepicker.js"></script>

    <!-- Select2 -->
    <script src="{$bootstrap}/select2-master/dist/js/select2.full.min.js"></script>

    <!-- Select2 -->
    <script>
      $(document).ready(function() {
        $("#conta.select2_multiple").select2({
          placeholder: "Escolha a Conta",
          allowClear: true
        });
        $("#filial.select2_multiple").select2({
          placeholder: "Escolha a filial",
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
          f = document.remessa;
          f.dataIni.value = start.format('DD/MM/YYYY');
          f.dataFim.value = end.format('DD/MM/YYYY');
        });
    </script>
  <!-- /daterangepicker -->