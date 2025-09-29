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
            <h2>Retorno Bancária - Cobrança
              <strong>
                {if $mensagem neq ''}
                  <div class="alert alert-info" role="alert">{$mensagem}</div>
                {/if}
              </strong>
            </h2>
            <ul class="nav navbar-right panel_toolbox">
              <li><button type="button" class="btn btn-warning" onClick="javascript:submitLetraRetorno();">
                  <span class="glyphicon glyphicon-zoom-in" aria-hidden="true"></span><span> Consolidação</span>
                </button>
              </li>
              <li><button type="button" class="btn btn-primary" onClick="javascript:submitConfirmaRetorno();">
                  <span class="glyphicon glyphicon-check" aria-hidden="true"></span><span> </span>Confirmar</button>
              </li>
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
            <form id="retorno" name="retorno" data-parsley-validate METHOD="POST"
              class="form-horizontal form-label-left" ACTION={$SCRIPT_NAME} enctype="multipart/form-data">
              <input name=mod type=hidden value="fin">
              <input name=form type=hidden value="retorno_bancario">
              <input name=letra type=hidden value={$letra}>
              <input name=submenu type=hidden value={$subMenu}>
              <input name=lanc type=hidden value={$lanc}>
              <input name=lancHeader type=hidden value={$lancHeader}>
              <input name=lancTreiller type=hidden value={$lancTreiller}>
              <input name=filePesquisa type=hidden value={$filePesquisa}>
              <input name=retorno type=hidden value={$retorno}>


              <div class="form-group col-md-2 col-sm-4 col-xs-4">
                <label class="">Selecine o arquivo de Retorno</label>
              </div>
              <div class="form-group col-md-4 col-sm-8 col-xs-8">
                <div class="fileinput fileinput-new" data-provides="fileinput">
                  <span class="btn btn-default btn-file"><input type="file" name="fileArq" /></span>
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
              {if $retorno eq 'P' or $retorno eq 'B'}

                <thead>
                  <tr class="headings">
                    <th>Num</th>
                    <th>Ocorrencia</th>
                    <th>Doc</th>
                    <th>Nosso Numero</th>
                    <th>Data Arq</th>
                    <th>NumDoc</th>
                    <th>Vencimento</th>
                    <th>Crédito</th>
                    <th>Valor Pago</th>
                    <th>Valor Lançado</th>
                  </tr>
                </thead>

                <tbody>
                  {if $retorno eq 'B'}
                    <tr class="even pointer info">
                      <td> </td>
                      <td COLSPAN=3 style="font-size:20px;">
                        <<< RETORNO PROCESSADO>>>
                      </td>
                      <td> </td>
                      <td> </td>
                      <td> </td>
                      <td> </td>
                      <td> </td>
                      <td> </td>
                    </tr>

                  {/if}
                  <tr>
                    <td> </td>
                    <td> Data Retorno:
                      {$lancHeader[0].dataArq|date_format:"%e %b, %Y"}</td>
                    <td> </td>
                    <td> Nome Arquivo:</td>
                    <td>{$filePesquisa}</td>
                    <td> </td>
                    <td> </td>
                    <td> </td>
                    <td> </td>
                    <td> </td>
                  </tr>
                  {section name=i loop=$lanc}
                    {assign var="total" value=$total+1}
                    <tr class="even pointer info">
                      {assign var="retornoTotal" value=$retornoTotal+$lanc[i].TOTAL}
                      {assign var="numReg" value=$numReg+1}
                      {if $lanc[i].numOcorrencia eq '17'}
                        {assign var="quantReg17" value=$quantReg17+1}
                        {assign var="valorReg17" value=$valorReg17+{$lanc[i].valorPago}}
                      {/if}

                      <td> {$numReg}</td>
                      <td> {$lanc[i].numOcorrencia} - {$lanc[i].descOcorrencia}</td>
                      <td> {$lanc[i].nf} - {$lanc[i].numControle} </td>
                      <td> {$lanc[i].idTituloBanco} </td>
                      <td> {$lanc[i].dataOcorrenciaBD|date_format:"%e %b, %Y"} </td>
                      <td> {$lanc[i].numDoc} </td>
                      {if $lanc[i].dataVencimentoBD neq '00-00-00'}
                        <td> {$lanc[i].dataVencimentoBD|date_format:"%e %b, %Y"} </td>
                      {else}
                        <td> </td>
                      {/if}
                      {if $lanc[i].dataCreditoBD neq '  -  -  '}
                        <td> {$lanc[i].dataCreditoBD|date_format:"%e %b, %Y"} </td>
                      {else}
                        <td> </td>
                      {/if}
                      <td align=right>{$lanc[i].valorPago|number_format:2:",":"."} </td>
                      <td align=right>{$lanc[i].total|number_format:2:",":"."} </td>
                    </tr>
                    <p>
                    {/section}
                    <tr class="even pointer danger">

                      <td> </td>
                      <td>Numero Registros retorno:</td>
                      <td>{$numReg}</td>
                      <td> </td>
                      <td> </td>
                      <td> </td>
                      <td> </td>
                      <td> </td>
                      <td> </td>
                      <td> </td>
                    </tr>
                    <tr>
                      <td> </td>
                      <td> 02 -Entrada Confirmada</td>
                      <td> Reg:</td>
                      <td>{$lancTrailler[0].quantReg02}</td>
                      <td> Total:</td>
                      <td>{$lancTrailler[0].valorReg02|number_format:2:",":"."}</td>
                      <td> </td>
                      <td> </td>
                      <td> </td>
                      <td> </td>
                    </tr>
                    <tr>
                      <td> </td>
                      <td> 09/10 - Baixado (sem recebimento)</td>
                      <td> Reg:</td>
                      <td>{$lancTrailler[0].quantReg0910Baixado}</td>
                      <td> Total:</td>
                      <td>{$lancTrailler[0].valorReg0910Baixado|number_format:2:",":"."}</td>
                      <td> </td>
                      <td> </td>
                      <td> </td>
                      <td> </td>
                    </tr>
                    <tr>
                      <td> </td>
                      <td> 06 -Liquidação Normal</td>
                      <td> Reg:</td>
                      <td>{$lancTrailler[0].quantReg06Liquidacao}</td>
                      <td> Total:</td>
                      <td>{$lancTrailler[0].valorReg06Liquidacao|number_format:2:",":"."}</td>
                      <!--td> Total:</td>
                            <td>{$lancTrailler[0].valorReg06|number_format:2:",":"."}</td-->
                      <td> </td>
                      <td> </td>
                      <td> </td>
                      <td> </td>
                    </tr>
                    <tr>
                      <td> </td>
                      <td> 17 - Liquidação após baixa ou Título não registrado</td>
                      <td> Reg:</td>
                      <td>{$quantReg17}</td>
                      <td> Total:</td>
                      <td>{$valorReg17|number_format:2:",":"."}</td>
                      <td> </td>
                      <td> </td>
                      <td> </td>
                      <td> </td>
                      <td> </td>
                      <td> </td>
                    </tr>

                </tbody>
              {else}

              {/if}

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