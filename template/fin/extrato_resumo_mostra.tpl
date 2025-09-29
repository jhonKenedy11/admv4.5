<style>
  .form-control,
  .x_panel {
    border-radius: 5px;
  }
</style>
<script type="text/javascript" src="{$pathJs}/fin/s_extrato.js"> </script>
<!-- page content -->
<div class="right_col" role="main">

  <div class="">
    <div class="row">


      <!-- panel principal  -->
      <div class="col-md-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2>Resumo - Cadastra Lançamentos Financeiros
              <strong>
                {if $mensagem neq ''}
                  <div class="alert alert-success" role="alert">Sucesso!&nbsp;{$mensagem}</div>
                {/if}
              </strong>
            </h2>
            <ul class="nav navbar-right panel_toolbox">
              <li><button type="button" class="btn btn-warning" onClick="javascript:limparCampos();">
                  <span class="glyphicon glyphicon-erase" aria-hidden="true"></span><span> Limpar Campos</span>
                </button>
              </li>

              <li><button type="button" class="btn btn-primary" onClick="javascript:submitCadastroResumo();">
                  <span class="glyphicon glyphicon-save" aria-hidden="true"></span><span> Cadastro Lancamentos
                    Financeiro</span>
                </button>
              </li>
              <li><button type="button" class="btn btn-danger" onClick="javascript:submitVoltar('');">
                  <span class="glyphicon glyphicon-backward" aria-hidden="true"></span><span> Voltar</span></button>
              </li>
            </ul>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <form id="lancamento" name="lancamento" data-parsley-validate METHOD="POST"
              class="form-horizontal form-label-left" ACTION={$SCRIPT_NAME}>
              <input name=mod type=hidden value="{$mod}">
              <input name=form type=hidden value="{$form}">
              <input name=letra type=hidden value={$letra}>
              <input name=submenu type=hidden value={$subMenu}>
              <input name=opcao type=hidden value="">
              <input name=genero type=hidden value={$genero}>


              <div class="title_left">
                <h3>Período : {$dataIni|date_format:"%e %b, %Y"} - {$dataFim|date_format:"%e %b, %Y"}</h3>
              </div>

              <div class="form-group">
                <div class="col-md-4 col-sm-12 col-xs-12">
                  <label for="generoRec">G&ecirc;nero</label>
                  <div class="input-group">
                    <select class="select2_multiple form-control" id="generoRec" name="generoRec">
                      {html_options values=$generoRec_ids selected=$generoRec_id output=$generoRec_names}
                    </select>
                  </div>
                </div>
                <div class="col-md-3 col-sm-12 col-xs-12">
                  <label for="centrocusto">Centro de Custo</label>
                  <select class="form-control" name=centrocusto id="centrocusto">
                    {html_options values=$centrocusto_ids selected=$centrocusto_id output=$centrocusto_names}
                  </select>
                </div>

                <div class="col-md-3 col-sm-6 col-xs-6  has-feedback">
                  <label for="datavenc">Data Vencimento:</label>
                  <input class="form-control has-feedback-left" type="text" id="datavenc" name="datavenc"
                    required="required" value={$datavenc}>
                  <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>
                </div>

                <div class="col-md-2 col-sm-6 col-xs-6">
                  <label for="conta">Conta Banc&aacute;ria</label>
                  <select class="form-control" name="conta" id="conta">
                    {html_options values=$conta_ids selected=$conta_id output=$conta_names}
                  </select>
                </div>

              </div>


            </form>

          </div> <!-- div class="x_content" = inicio tabela -->
        </div> <!-- div class="x_panel" = painel principal-->



        <!-- panel tabela dados -->
        <div class="col-md-12 col-xs-12">
          <div class="x_panel">
            <!--table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap table-condensed" cellspacing="0" width="100%"-->
            <table id="datatable-buttons" class="table table-bordered jambo_table">
              <thead>
                <!-- tr class="headings">
                                <th><h4>Período: {$dataIni} - {$dataFim}</h4></th>
                            </tr-->
                <tr class="headings">
                  <th>Nome</th>
                  <th>A Receber</th>
                  <th>A Pagar</th>
                  <th>Total</th>
                </tr>
              </thead>

              <tbody>

                {section name=i loop=$lanc}
                  {assign var="total" value=$total+1}

                  <td> {$lanc[i].NOMEREDUZIDO} - {$lanc[i].NOME} </td>
                  <td align=right>{$lanc[i].PAGAMENTO|number_format:2:",":"."} </td>
                  <td align=right>{$lanc[i].RECEBIMENTO|number_format:2:",":"."} </td>
                  <td align=right>{$lanc[i].TOTAL|number_format:2:",":"."} </td>
                  </tr>
                  <p>
                  {/section}

              </tbody>

            </table>

          </div> <!-- div class="x_content" = inicio tabela -->
        </div> <!-- div class="x_panel" = painel principal-->
      </div> <!-- div class="col-md-12 col-sm-12 col-xs-12 "-->
    </div> <!-- div class="row "-->
  </div> <!-- class='' = controla menu user -->



  {include file="template/database.inc"}

  <!-- /Datatables -->

  <!-- /Datatables -->
  <script>
    $("#generoRec.select2_multiple").select2({
      placeholder: "Escolha o Genêro Recebimento",
      allowClear: true
    });
    $("#generoPag.select2_multiple").select2({
      placeholder: "Escolha o Genêro Pagamento",
      allowClear: true
    });
    $(function() {
      $('#datavenc').daterangepicker({
        singleDatePicker: true,
        calender_style: "picker_1",
        locale: {
          format: 'DD/MM/YYYY',
          daysOfWeek: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
          monthNames: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro',
            'Outubro', 'Novembro', 'Dezembro'
          ],
        }

      });
    });
</script>