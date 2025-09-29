<style type="text/css">
  .form-control {
    border-radius: 5px;
  }

  #div_btn {
    margin-bottom: 10px 15px;
    padding: 5px;
    display: flex;
    justify-content: center;
  }

  #div_btn .nav.navbar-right.panel_toolbox {
    display: flex;
    gap: 10px;
    list-style: none;
    padding-left: 0;
    margin: 0 auto;
  }

  .input-sm {
    padding: 1px !important;
    height: 20px;
    font-size: 9.5px;
    line-height: 1.2;
    min-width: 100px;
  }

  .row-equal {
    display: flex;
    flex-wrap: wrap;
    margin: 0 -5px;
    align-items: center;
  }

  .row-equal>div {
    padding: 1px !important;
    flex: 1;
    min-width: 100px;
    margin-bottom: 0px;
  }

  .row-equal label {
    margin-top: -5px !important;
    margin-bottom: -1px;
    height: 25px;
    font-size: 10px;
    padding-top: 6px !important;
  }

  .row-equal select {
    margin-top: 0;
    margin-bottom: 0px;
    padding: 1px !important;
    height: 23px;
    font-size: 9.5px;
  }

  table {
    width: 100%;
    margin: 0px;

  }

  td,
  tr,
  th {
    width: 100%;
    padding: 1px !important;
    font-size: 9.5px;

  }
  .input-error {    
    background-color: #ffe6e6c7 !important;
  }
</style>

<script type="text/javascript" src="{$pathSweet}/dist/sweetalert2.all.min.js"></script>
<script type="text/javascript" src="{$pathJs}/cat/s_apontamento_os_mobile.js"> </script>
<!-- page content -->
<div class="right_col" role="main">
  <div class="">

    <form id="lancamento" data-parsley-validate class="form-horizontal form-label-left" NAME="lancamento"
      ACTION="{$SCRIPT_NAME}" METHOD="POST">
      <input name=mod type=hidden value="cat">
      <input name=form type=hidden value="apontamento_os_mobile">
      <input name=submenu type=hidden value={$subMenu}>
      <input name=letra type=hidden value={$letra}>

      <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
          <div class="x_panel">
            <div>
              <h2>
                <center>Apontamento O.S.</center>
              </h2>
            </div>

            <div class="btn-group col-xs-12 col-sm-6 col-md-8" id="div_btn">
              <ul class="nav navbar-right panel_toolbox">
                <li><button type="button" class="btn btn-danger btn-sm" onClick="javascript:submitVoltar();">
                    <span class="glyphicon glyphicon-backward" aria-hidden="true"></span><span> Voltar</span></button>
                </li>
                <li><button type="button" class="btn btn-primary btn-sm" onClick="javascript:submitConfirmar();">
                    <span class="glyphicon glyphicon-floppy-save" aria-hidden="true"></span><span>
                      Confirmar</span></button>
                </li>
              </ul>
            </div>

            <div class="clearfix"></div>
            <div class="x_content">



              <div class="row-equal">
                <!-- Número da OS -->
                <div class="form-group col-xs-6 compact-form-group">
                  <label class="control-label">Número da O.S.</label>
                  <input id="numero_os" name="numero_os" type="text" readonly class="form-control input-sm"
                    value="{$numero_os}">
                </div>

                <!-- Nome do cliente -->
                <div class="form-group col-xs-6 compact-form-group">
                  <label class="control-label">Cliente</label>
                  <input id="nome_cliente" name="nome_cliente" type="text" readonly class="form-control input-sm"
                    value="{$nome_cliente}">
                </div>
              </div>

              <!-- Datas importantes -->
              <div class="row-equal">
                <!-- Data de início -->
                <div class="form-group col-xs-6 compact-form-group">
                  <label class="control-label">Data Início</label>
                  <input id="data_inicio" name="data_inicio" type="date" readonly class="form-control input-sm"
                    value={$data_inicio}>
                </div>

                <!-- Prazo de entrega -->
                <div class="form-group col-xs-6 compact-form-group">
                  <label class="control-label">Prazo Entrega</label>
                  <input id="prazo_entrega" name="prazo_entrega" type="date" readonly class="form-control input-sm"
                    value={$prazo_entrega}>
                </div>

              </div>
              <div class="row-equal">
                <!-- Data de finalização -->
                <div class="form-group col-xs-6 compact-form-group">
                  <label class="control-label">Data Finalização</label>
                  <input id="data_finalizacao" name="data_finalizacao" type="text" required
                    class="form-control input-sm" value="{$data_finalizacao}">
                </div>
                <div class="form-group col-xs-6 compact-form-group">
                  <label class="control-label">Status</label>
                  <select class="form-control input-sm" name="situacao" id="situacao">
                    {html_options values=$situacao_ids output=$situacao_names selected=$situacao_id}
                  </select>
                </div>
              </div>

              <!-- Serviços (dynamic list with actions) -->
              <div class="row-equal">
                <table class="table table-bordered jambo_table">
                  <thead>
                    <tr>
                      <th>Quantidade OS</th>
                      <th>Executada</th>
                    </tr>
                  </thead>
                  <tbody>
                    {if $lanc|count > 0}
                      {section name=i loop=$lanc}
                        <!-- Linha da descrição -->
                        <tr>
                          <td colspan="2">{$lanc[i].DESCSERVICO}</td>
                        </tr>

                        <!-- Linha das quantidades -->
                        <tr class="even pointer">
                          <td>
                            <input type="text" name="qtd_exec" value="{$lanc[i].QUANTIDADE}" class="form-control input-sm"
                              readonly>
                            <input type="hidden" name="qtd_saldo" value="{$lanc[i].QTD_SALDO}">
                            <input type="hidden" name="qtd_contratada" value="{$lanc[i].QUANTIDADE}">

                          <td>
                            <input type="hidden" name="id_servico" value="{$lanc[i].ID}">
                            <input class="form-control money input-sm" name="quantidade_executada"
                              onchange="validateExecutada(event)">
                          </td>
                        </tr>
                      {/section}
                    {else}
                      <tr>
                        <td colspan="3">Nenhum serviço cadastrado</td>
                      </tr>
                    {/if}
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
    </form>
  </div>
</div>

{include file="template/database.inc"}

<script type="text/javascript">
  $('input[name="data_finalizacao"]').daterangepicker({
      singleDatePicker: true,
      startDate: moment(),
      drops: 'auto',

      ranges: {
        'Hoje': [moment(), moment()],
        'Ontem': [moment().subtract(1, 'days'), moment().subtract(1, 'days')]
      },
      locale: {
        format: 'DD/MM/YYYY',
        applyLabel: 'Confirma',
        cancelLabel: 'Limpa',
        fromLabel: 'Início',
        toLabel: 'Fim',
        opens: 'center',
        customRangeLabel: 'Calendário',
        daysOfWeek: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
        monthNames: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro',
          'Outubro', 'Novembro', 'Dezembro'
        ],
        firstDay: 1
      }
    },

    // Função para atualizar o campo quando uma data é selecionada
    function(start, end, label) {
      // Atualiza diretamente o campo "data_finalizacao"
      $('input[name="data_finalizacao"]').val(start.format('DD/MM/YYYY'));
    });

  // Define o valor inicial do campo para a data atual (opcional)
  $('input[name="data_finalizacao"]').val(moment().format('DD/MM/YYYY'));
</script>

<script src="https://cdn.rawgit.com/plentz/jquery-maskmoney/master/dist/jquery.maskMoney.min.js"></script>
<script>
  $(document).ready(function() {
    $(".money").maskMoney({
      decimal: ",",
      thousands: ".",
      allowZero: true,
      precision: 2,
    });
  });
</script>

<script>
  // função para validar valores maiores do que exec/saldo da os.
  function validateExecutada(event) {
    //pega o valor do event
    const input = event.target;

    // atribui o valor do input a uma constante
    const valor = input.value.replace(/\./g, '').replace(',', '.');
    //transforma o valor em int
    const executada = parseFloat(valor) || 0;

    const row = input.closest('tr');
    //transforma o qtd_exec em int
    const qtdExec = parseFloat(row.querySelector('[name="qtd_exec"]').value) || 0;
    
    // faz a comparacao entre os valores
    input.classList.toggle('input-error', executada > qtdExec);
  }
</script>