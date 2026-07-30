<style type="text/css">
  input[type="number"]::-webkit-outer-spin-button,
  input[type="number"]::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
  }

  input[type="number"] {
    -moz-appearance: textfield;
  }

  .form-control,
  .x_panel {
    border-radius: 5px;
  }

  .radio-group {
      display: flex;
      gap: 15px;
  }

  .radio-group label {
      display: flex;
      align-items: center;
      white-space: nowrap;
  }
  
  /* Tema Roxo Global */
  .x_title h2 {
    color: #667eea;
  }
  
  .x_title h2 i {
    color: #764ba2;
  }

  .section-title {
    font-size: 16px;
    font-weight: 600;
    color: #667eea;
    margin-bottom: 15px;
    padding-bottom: 10px;
    border-bottom: 2px solid #667eea;
    position: relative;
  }
  
  .section-title:before {
    content: '';
    position: absolute;
    bottom: -2px;
    left: 0;
    width: 60px;
    height: 2px;
    background: #764ba2;
  }
  
  .section-title i {
    color: #764ba2;
    margin-right: 8px;
  }
  
  .param-box {
    background: linear-gradient(to bottom, #f8f7ff 0%, #ffffff 100%);
    padding: 20px;
    border-radius: 8px;
    margin-bottom: 20px;
    border: 2px solid #e9e4ff;
    box-shadow: 0 2px 10px rgba(102, 126, 234, 0.1);
  }
  
  .param-item {
    display: flex;
    align-items: center;
    padding: 10px 15px;
    background: white;
    border-radius: 6px;
    margin-bottom: 10px;
    border: 1px solid #e9e4ff;
    transition: all 0.3s;
  }
  
  .param-item:hover {
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.2);
    border-color: #667eea;
    transform: translateY(-2px);
  }
  
  .param-item label {
    margin: 0;
    font-weight: 500;
    color: #4b5563;
    flex: 1;
  }
  
  .param-item label i {
    color: #764ba2;
    margin-right: 8px;
  }
  
  .param-item .radio-group {
    display: flex;
    gap: 20px;
  }
  
  .tributos-box {
    background: white;
    padding: 20px;
    border-radius: 8px;
    margin-bottom: 20px;
    border: 2px solid #e9e4ff;
    box-shadow: 0 2px 10px rgba(102, 126, 234, 0.08);
  }

  .tributos-box label {
    color: #4b5563;
    font-weight: 500;
  }

  .tributo-item {
    background: linear-gradient(to bottom, #f8f7ff 0%, #ffffff 100%);
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 15px;
    border: 2px solid #e9e4ff;
    transition: all 0.3s;
  }

  .tributo-item:hover {
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.2);
    border-color: #667eea;
    transform: translateY(-2px);
  }

  .tributo-item:last-child {
    margin-bottom: 0;
  }

  .tributo-item label i {
    color: #764ba2;
  }
  
  .input-with-icon {
    position: relative;
  }
  
  .input-icon {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    font-weight: 600;
    color: #667eea;
  }
  
  .input-icon-right {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    font-weight: 600;
    color: #667eea;
  }

  .form-control.has-icon-right {
    padding-right: 35px;
  }
  
  .form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
  }
  
  .info-card {
    background: white;
    border-radius: 8px;
    padding: 20px;
    border: 2px solid #e9e4ff;
    margin-bottom: 20px;
    box-shadow: 0 2px 10px rgba(102, 126, 234, 0.08);
  }
  
  .badge-required {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 3px 10px;
    border-radius: 12px;
    font-size: 10px;
    margin-left: 8px;
    font-weight: 600;
    text-transform: uppercase;
  }
  
  /* Alerts personalizados */
  .alert-success {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    border: none;
    color: white;
    border-left: 4px solid #047857;
  }
  
  .alert-danger {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    border: none;
    color: white;
    border-left: 4px solid #b91c1c;
  }
  
  /* Textarea personalizado */
  textarea.form-control {
    border: 2px solid #e9e4ff;
    transition: all 0.3s;
  }
  
  textarea.form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
  }
  
  /* Select personalizado */
  select.form-control {
    border: 2px solid #e9e4ff;
    transition: all 0.3s;
  }
  
  select.form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
  }
  
  /* Input personalizado */
  input.form-control {
    border: 2px solid #e9e4ff;
    transition: all 0.3s;
  }
  
  input.form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
  }
  
  /* Linha divisória */
  .ln_solid {
    border-top: 3px solid #667eea;
    margin: 20px 0;
  }
  
  /* Panel personalizado */
  .x_panel {
    border-top: 4px solid #667eea;
    box-shadow: 0 4px 20px rgba(102, 126, 234, 0.1);
  }
  
  /* Labels com destaque roxo */
  .control-label {
    color: #4b5563;
    font-weight: 500;
  }
</style>

<script type="text/javascript" src="{$pathSweet}/dist/sweetalert2.all.min.js"></script>
<script type="text/javascript" src="{$pathJs}/fin/s_consolidacao_bancaria.js"> </script>

<!-- page content -->
<div class="right_col" role="main">
  <div class="">
    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2><i class="fa fa-calculator"></i> Consolidação Bancária - Histórico</h2>
            <ul class="nav navbar-right panel_toolbox">
              <li>
                <button type="button" class="btn btn-primary" onclick="location.href='?mod=fin&form=consolidacao_bancaria&action=edita'">
                  <i class="fa fa-plus-circle"></i> Nova Consolidação
                </button>
              </li>
            </ul>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            {if $mensagem neq ''}
              <div class="alert alert-success" role="alert" style="margin-top: 15px;">
                <strong><i class="fa fa-check-circle"></i> {$mensagem}</strong>
              </div>
            {/if}

            <form id="consolidacao_filtros" name="consolidacao_filtros" METHOD="POST" class="form-horizontal form-label-left" ACTION={$SCRIPT_NAME}>
              <input name=mod type=hidden value="fin">
              <input name=form type=hidden value="consolidacao_bancaria">
              <input name=letra type=hidden value={$letra}>

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
                  <input type="text" name="dataConsulta" id="dataConsulta" class="form-control" value="{$dataIni} - {$dataFim}">
                </div>
              </div>

              <div class="form-group col-md-3 col-sm-12 col-xs-12">
                <label>Conta Banc&aacute;ria</label>
                <select class="select2_multiple form-control" multiple="multiple" id="conta" name="conta">
                  {html_options values=$conta_ids selected=$conta_id output=$conta_names}
                </select>
              </div>

              <div class="form-group col-md-3 col-sm-12 col-xs-12">
                <label>Filial</label>
                <select class="select2_multiple form-control" multiple="multiple" id="filial" name="filial">
                  {html_options values=$filial_ids selected=$filial_id output=$filial_names}
                </select>
              </div>

              <div class="clearfix"></div>

              <div class="form-group">
                <div class="col-md-6 col-sm-12 col-xs-12">
                  <div class="input-group">
                    <input type="text" class="form-control" id="nome" name="nome" placeholder="Conta / Pessoa" value="{$nome}">
                    <span class="input-group-btn">
                      <button type="button" class="btn btn-primary" onClick="javascript:abrir('{$pathCliente}/index.php?mod=crm&form=contas&opcao=pesquisar');">
                        <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                      </button>
                    </span>
                  </div>
                </div>
              </div>

            </form>

            <div class="info-card">
              <h4 class="section-title"><i class="fa fa-list"></i> Resultados</h4>
              <!-- Tabela de exemplo; preencher com dados reais -->
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th>Data</th>
                    <th>Conta</th>
                    <th>Descrição</th>
                    <th>Valor</th>
                    <th>Ações</th>
                  </tr>
                </thead>
                <tbody id="consolidacao-list">
                  <!-- linhas populadas pelo backend ou JS -->
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

{include file="template/form.inc"}

