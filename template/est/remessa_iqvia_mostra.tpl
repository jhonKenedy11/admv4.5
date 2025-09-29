<style>
  .form-control,
  .x_panel {
    border-radius: 5px;
  }
</style>
<script type="text/javascript" src="{$pathJs}/est/s_est.js"> </script>
<!-- page content -->
<div class="right_col" role="main">

  <div class="">
    <div class="row">

      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2>Remessa IQVIA
              <strong>
                {if $nomeArq neq ''}
                  <a href="{$arquivo}" download>">Download Remessa - {$nomeArq}</a>

                  {/if}
                </strong>
              </h2>
              <ul class="nav navbar-right panel_toolbox">
                <li><button type="button" class="btn btn-warning" onClick="javascript:submitLetraIqvia();">
                    <span class="glyphicon glyphicon-zoom-in" aria-hidden="true"></span><span> Gerar Arquivos</span>
                  </button>
                </li>
                <li class="dropdown">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i
                      class="fa fa-wrench"></i></a>
                  <ul class="dropdown-menu" role="menu">
                    <li>
                      <button type="button" class="btn btn-danger btn-xs" data-toggle="modal"
                        data-target="#modalTeste"><span>Enviar E-mail</span></button>
                    </li>
                  </ul>
                </li>
                {* <li><a class="close-link"><i class="fa fa-close"></i></a>
                  </li> *}
              </ul>
              <div class="clearfix"></div>
            </div>
            <div class="x_content">
              <form id="remessa" name="remessa" data-parsley-validate METHOD="POST"
                class="form-horizontal form-label-left" ACTION={$SCRIPT_NAME}>
                <input name=mod type=hidden value="est">
                <input name=form type=hidden value="remessa_iqvia">
                <input name=letra type=hidden value={$letra}>
                <input name=submenu type=hidden value={$subMenu}>
                <input name=lanc type=hidden value={$lanc}>
                <input name=email type=hidden value={$email}>

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
                  <select class="select2_multiple form-control" multiple="multiple" id="filial" name="filial">



                  {html_options values=$filial_ids selected=$filial_id output=$filial_names}
                  </select>
                </div>

                <!-- Modal EMAIL -->
                <div class="modal fade" id="modalTeste" role="dialog">
                  <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h6 class="modal-title">Para...</h6>
                        <textarea class="form-control" placeholder="Digite o email." rows="1" id="emailEndereco"
                          name="emailEndereco">{$emailEndereco}</textarea>
                        <h6 class="modal-title">Assunto</h6>
                        <textarea class="form-control" placeholder="Digite o assunto." rows="1" id="emailTitulo"
                          name="emailTitulo">{$emailTitulo}</textarea>
                      </div>
                      <div class="modal-body">
                        <textarea class="form-control" placeholder="Digite o corpo do email." rows="4" id="emailCorpo"
                          name="emailCorpo">{$emailCorpo}</textarea>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal"
                          onClick="javascript:submitLetraIqviaEMAIL();">Enviar</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                      </div>
                    </div>
                  </div>
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
                      <td> T O T A L - Num. Reg:{$numReg}</td>
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
          f = document.lancamento;
          f.dataIni.value = start.format('DD/MM/YYYY');
          f.dataFim.value = end.format('DD/MM/YYYY');
        });
    </script>
  <!-- /daterangepicker -->