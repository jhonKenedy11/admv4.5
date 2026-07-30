<style>
  .form-control,
  .x_panel,
  .select2-selection  {
    border-radius: 5px !important;
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
                <li>
                  <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modalVisualizadorTxt" title="Visualizar registros do arquivo TXT de remessa">
                    <span class="fa fa-file-text-o" aria-hidden="true"></span><span> Visualizar de Remessa</span>
                  </button>
                </li>
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



                <div class="form-group col-md-3 col-sm-12 col-xs-12">
                  <label class="">Per&iacute;odo Emissão</label>
                  <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                  <div>
                    <input type="text" name="dataConsulta" id="dataConsulta" class="form-control" style="height: 38px !important;"
                      value="{$dataIni} - {$dataFim}">
                  </div>
                </div>

                <div class="form-group col-md-3 col-sm-12 col-xs-12">
                  <label>Conta Bancária</label>
                  <select class="select2_multiple form-control" multiple="multiple" id="conta" name="contaBanco">

                  {html_options values=$conta_ids selected=$conta_id output=$conta_names}
                  </select>
                </div>

                <div class="form-group col-md-3 col-sm-12 col-xs-12">
                  <label>Filial</label>
                  <select class="select2_multiple form-control" multiple="multiple" id="filial" name="filial">

                  {html_options values=$filial_ids selected=$filial_id output=$filial_names}
                  </select>
                </div>

                <div class="form-group col-md-3 col-sm-12 col-xs-12">
                  <div style="display: flex; flex-wrap: wrap; justify-content: flex-end; gap: 5px; margin-top: 25px;">
                    <button type="button" class="btn btn-warning" onClick="javascript:submitLetraRemessa();">
                      <span class="glyphicon glyphicon-zoom-in" aria-hidden="true"></span><span> Pesquisa</span>
                    </button>
                    <button type="button" class="btn btn-primary" onClick="javascript:submitConfirmaRemessa();">
                      <span class="glyphicon glyphicon-check" aria-hidden="true"></span><span> Confirma</span>
                    </button>
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
                  {if $numReg > 0}
                    <tr class="even pointer danger">

                      <td> </td>
                      <td> T O T A L R E M E S S A - Num. Reg:{$numReg}</td>
                      <td> </td>
                      <td> </td>
                      <td> </td>
                      <td> </td>
                      <td align=right>{$recebimentoTotal|number_format:2:",":"."} </td>
                    </tr>
                  {/if}
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

  <!-- ============================================================
       MODAL: Visualizador de Arquivo Remessa TXT
       ============================================================ -->
  <div class="modal fade" id="modalVisualizadorTxt" tabindex="-1" role="dialog" aria-labelledby="modalVisualizadorTxtLabel">
    <div class="modal-dialog" style="width:92%; max-width:1150px;" role="document">
      <div class="modal-content" style="border-radius:6px;">

        <div class="modal-header" style="background:#2c6fad; color:#fff; border-radius:5px 5px 0 0; padding:14px 20px;">
          <button type="button" class="close" data-dismiss="modal" aria-label="Fechar" style="color:#fff;opacity:1;font-size:26px;margin-top:-4px;">
            <span aria-hidden="true">&times;</span>
          </button>
          <h4 class="modal-title" id="modalVisualizadorTxtLabel" style="font-weight:600;">
            <i class="fa fa-file-text-o"></i>&nbsp; Visualizador de Arquivo Remessa TXT
          </h4>
        </div>

        <div class="modal-body" style="max-height:calc(90vh - 130px); overflow-y:auto; padding:20px;">

          <!-- Upload area -->
          <div class="row" id="vtz-upload-area">
            <div class="col-md-4">
              <div class="form-group">
                <label style="font-weight:600;"><i class="fa fa-university"></i> Banco</label>
                <select class="form-control" id="vtz-banco">
                  <option value="">-- Selecione o Banco --</option>
                  <option value="sicredi">Sicredi (748)</option>
                </select>
              </div>
            </div>
            <div class="col-md-5">
              <div class="form-group">
                <label style="font-weight:600;"><i class="fa fa-upload"></i> Arquivo TXT de Remessa</label>
                <input type="file" id="vtz-arquivo" accept=".txt,.rem,.REM" class="form-control" style="padding:4px 8px; height:auto;">
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label>&nbsp;</label>
                <button type="button" id="vtz-btn-visualizar" class="btn btn-success btn-block" style="font-weight:600;">
                  <i class="fa fa-eye"></i>&nbsp; Visualizar
                </button>
              </div>
            </div>
          </div>

          <!-- Resultado (preenchido pelo JS) -->
          <div id="vtz-resultado" style="display:none; margin-top:5px;"></div>

        </div>

        <div class="modal-footer" style="border-top:1px solid #e5e5e5; padding:12px 20px;">
          <button type="button" class="btn btn-default" data-dismiss="modal">
            <i class="fa fa-times"></i> Fechar
          </button>
        </div>

      </div>
    </div>
  </div>
  <!-- /MODAL Visualizador TXT -->

  <script>
  {literal}
  $(document).ready(function () {

    /* ---- extrai substring 1-indexed ---- */
    function c(linha, de, ate) { return linha.substring(de - 1, ate); }

    /* ---- formata datas ---- */
    function d6(s) {  // DDMMAA -> DD/MM/20AA
      s = s.replace(/\s/g, '');
      return s.length === 6 ? s.substr(0,2)+'/'+s.substr(2,2)+'/20'+s.substr(4,2) : '—';
    }
    function d8(s) {  // AAAAMMDD -> DD/MM/AAAA
      s = s.replace(/\s/g, '');
      return s.length === 8 ? s.substr(6,2)+'/'+s.substr(4,2)+'/'+s.substr(0,4) : '—';
    }

    /* ---- formata valor (centavos) ---- */
    function val(s) {
      var n = parseInt(s, 10);
      return isNaN(n) ? '0,00' : (n/100).toLocaleString('pt-BR',{minimumFractionDigits:2});
    }

    /* ---- formata CNPJ/CPF ---- */
    function doc(s, tipo) {
      s = s.replace(/\D/g,'').replace(/^0+/,'');
      if (tipo === '2') { s = s.padStart(14,'0'); return s.replace(/^(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})$/,'$1.$2.$3/$4-$5'); }
      s = s.padStart(11,'0'); return s.replace(/^(\d{3})(\d{3})(\d{3})(\d{2})$/,'$1.$2.$3-$4');
    }

    var INSTRUCAO = {
      '01':'Cadastro','02':'Baixa','04':'Abatimento','05':'Canc.Abatimento',
      '06':'Alt.Vencimento','09':'Protesto','18':'Sust.Protesto/Baixar',
      '19':'Sust.Protesto/Carteira','31':'Alt.Outros Dados',
      '45':'Incluir Negativação','75':'Excl.Negat./Carteira','76':'Excl.Negat./Baixar'
    };
    var COR = {'01':'#27ae60','02':'#e74c3c','06':'#2980b9','09':'#c0392b','31':'#8e44ad'};

    /* ==================================================
       PARSE DO ARQUIVO
       ================================================== */
    function parsear(conteudo) {
      var linhas = conteudo.replace(/\r\n/g,'\n').replace(/\r/g,'\n').split('\n');
      var header = null, detalhes = [], trailler = null;

      linhas.forEach(function(raw) {
        if (!raw.trim()) return;
        var l = raw.length < 400 ? raw + ' '.repeat(400 - raw.length) : raw;

        if (l[0] === '0') {
          header = {
            banco:     c(l,77,79) + ' – ' + c(l,80,94).trim(),
            codBenef:  c(l,27,31).replace(/^0+/,''),
            cnpj:      c(l,32,45).trim(),
            dataGer:   d8(c(l,95,102)),
            numRem:    c(l,111,117).replace(/^0+/,'')
          };
        } else if (l[0] === '1') {
          detalhes.push({
            instrucao:  c(l,109,110),
            lancamento: c(l,111,120).replace(/^0+/,''),
            nossoNum:   c(l,48,56).trim(),
            vencimento: d6(c(l,121,126)),
            valor:      val(c(l,127,139)),
            emissao:    d6(c(l,151,156)),
            especie:    c(l,149,149),
            aceite:     c(l,150,150),
            juros:      val(c(l,161,173)),
            tipoJuros:  c(l,19,19),
            desconto:   val(c(l,180,192)),
            tipoDesc:   c(l,18,18),
            protesto:   c(l,157,158) === '06' ? parseInt(c(l,159,160),10)+' dias' : 'Não',
            tipoPessoa: c(l,219,219),
            cnpjCpf:    doc(c(l,221,234), c(l,219,219)),
            nome:       c(l,235,274).trim(),
            endereco:   c(l,275,314).trim(),
            cep:        c(l,327,334).trim().replace(/^(\d{5})(\d{3})$/,'$1-$2')
          });
        } else if (l[0] === '9') {
          trailler = { banco: c(l,3,5), codBenef: c(l,6,10).replace(/^0+/,'') };
        }
      });

      return { header: header, detalhes: detalhes, trailler: trailler };
    }

    /* ==================================================
       MONTAGEM DO HTML
       ================================================== */
    function montar(res) {
      var html = '';
      var totalValor = res.detalhes.reduce(function(acc,d){ return acc + (parseFloat(d.valor.replace(/\./g,'').replace(',','.'))||0); }, 0);

      /* Banner */
      html += '<div class="alert alert-info"><b><i class="fa fa-bank"></i> Sicredi (748)</b>'
            + ' &nbsp;&nbsp; <b>'+res.detalhes.length+'</b> título(s)'
            + ' &nbsp;&nbsp; Total: <b>R$ '+totalValor.toLocaleString('pt-BR',{minimumFractionDigits:2})+'</b></div>';

      /* Header */
      if (res.header) {
        var h = res.header;
        html += '<div class="panel panel-info"><div class="panel-heading"><b>Header — Identificação do Arquivo</b></div>'
              + '<div class="panel-body"><div class="row">'
              + '<div class="col-sm-3"><small class="text-muted">Banco</small><br><b>'+h.banco+'</b></div>'
              + '<div class="col-sm-3"><small class="text-muted">Beneficiário</small><br><b>'+h.codBenef+'</b></div>'
              + '<div class="col-sm-3"><small class="text-muted">CNPJ</small><br><b>'+h.cnpj+'</b></div>'
              + '<div class="col-sm-3"><small class="text-muted">Data Geração / Nº Remessa</small><br><b>'+h.dataGer+' / '+h.numRem+'</b></div>'
              + '</div></div></div>';
      }

      /* Detalhes */
      res.detalhes.forEach(function(d, i) {
        var cor  = COR[d.instrucao] || '#7f8c8d';
        var inst = INSTRUCAO[d.instrucao] || d.instrucao;
        var jrs  = d.tipoJuros === 'B' ? d.juros+'%/dia' : 'R$ '+d.juros+'/dia';
        var dsc  = d.tipoDesc  === 'B' ? d.desconto+'%'  : 'R$ '+d.desconto;

        html += '<div class="panel panel-default" style="border-left:4px solid '+cor+'; margin-bottom:6px;">'
              + '<div class="panel-heading" style="background:#fafafa; padding:7px 12px;">'
              + '  <div class="row">'
              + '    <div class="col-sm-8"><b><i class="fa fa-file-o" style="color:'+cor+'"></i> Reg. '+(i+1)+' — Lanç. '+d.lancamento+'</b></div>'
              + '    <div class="col-sm-4 text-right"><span class="label" style="background:'+cor+'">'+d.instrucao+' – '+inst+'</span></div>'
              + '  </div>'
              + '</div>'
              + '<div class="panel-body" style="padding:8px 12px;">'

              /* linha 1: pagador */
              + '<div class="row">'
              + '  <div class="col-sm-4"><small class="text-muted">Pagador</small><br><b>'+d.nome+'</b><br><small>'+(d.tipoPessoa==='2'?'PJ':'PF')+' – '+d.cnpjCpf+'</small></div>'
              + '  <div class="col-sm-4"><small class="text-muted">Endereço / CEP</small><br><small>'+d.endereco+'</small><br><small>'+d.cep+'</small></div>'
              + '  <div class="col-sm-2"><small class="text-muted">Nosso Nº</small><br><b>'+d.nossoNum+'</b></div>'
              + '  <div class="col-sm-2 text-right"><small class="text-muted">Valor</small><br><b style="font-size:15px; color:#2c6fad;">R$ '+d.valor+'</b></div>'
              + '</div>'

              /* linha 2: datas e financeiro */
              + '<hr style="margin:6px 0;">'
              + '<div class="row">'
              + '  <div class="col-sm-2"><small class="text-muted">Vencimento</small><br><b>'+d.vencimento+'</b></div>'
              + '  <div class="col-sm-2"><small class="text-muted">Emissão</small><br><b>'+d.emissao+'</b></div>'
              + '  <div class="col-sm-2"><small class="text-muted">Espécie / Aceite</small><br><small>'+d.especie+' / '+d.aceite+'</small></div>'
              + '  <div class="col-sm-2"><small class="text-muted">Juros</small><br><small>'+jrs+'</small></div>'
              + '  <div class="col-sm-2"><small class="text-muted">Desconto</small><br><small>'+dsc+'</small></div>'
              + '  <div class="col-sm-2"><small class="text-muted">Protesto</small><br><small>'+d.protesto+'</small></div>'
              + '</div>'

              + '</div></div>';
      });

      /* Trailler */
      if (res.trailler) {
        html += '<div class="panel panel-warning"><div class="panel-heading"><b>Trailler — Totais</b></div>'
              + '<div class="panel-body"><div class="row">'
              + '<div class="col-sm-4"><small class="text-muted">Banco</small><br><b>'+res.trailler.banco+'</b></div>'
              + '<div class="col-sm-4 text-center"><small class="text-muted">Qtd. Títulos</small><br><b style="font-size:22px;">'+res.detalhes.length+'</b></div>'
              + '<div class="col-sm-4 text-right"><small class="text-muted">Valor Total</small><br><b style="font-size:18px; color:#27ae60;">R$ '+totalValor.toLocaleString('pt-BR',{minimumFractionDigits:2})+'</b></div>'
              + '</div></div></div>';
      }

      return html;
    }

    /* ==================================================
       EVENTOS
       ================================================== */
    $('#vtz-btn-visualizar').on('click', function () {
      var banco = $('#vtz-banco').val();
      var file  = document.getElementById('vtz-arquivo').files[0];

      if (!banco) { Swal.fire({icon:'warning', title:'Atenção', text:'Selecione o banco.'}); return; }
      if (!file)  { Swal.fire({icon:'warning', title:'Atenção', text:'Selecione o arquivo TXT.'}); return; }

      var $btn = $(this).html('<i class="fa fa-spinner fa-spin"></i> Aguarde...').prop('disabled', true);
      var reader = new FileReader();

      reader.onload = function (e) {
        try {
          var res  = parsear(e.target.result);
          var html = montar(res);
          $('#vtz-resultado').html(html).show();
        } catch(err) {
          Swal.fire({icon:'error', title:'Erro', text: err.message});
        } finally {
          $btn.html('<i class="fa fa-eye"></i> Visualizar').prop('disabled', false);
        }
      };
      reader.readAsText(file, 'ISO-8859-1');
    });

    $('#modalVisualizadorTxt').on('hidden.bs.modal', function () {
      $('#vtz-banco').val('');
      document.getElementById('vtz-arquivo').value = '';
      $('#vtz-resultado').html('').hide();
    });

  });  /* fim $(document).ready */
  {/literal}
  </script>