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
    <form id="consolidacaoForm" class="form-horizontal form-label-left" action="{$SCRIPT_NAME}" method="post">
      <input type="hidden" name="mod" value="fin">
      <input type="hidden" name="form" value="consolidacao_bancaria">
      <input type="hidden" name="action" value="gerar">

      <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
          <div class="x_panel">
            <div class="x_title">
              <h2><i class="fa fa-edit"></i> Consolidação Bancária - Novo</h2>
              <ul class="nav navbar-right panel_toolbox">
                <li>
                  <button type="button" class="btn btn-primary" onclick="document.getElementById('consolidacaoForm').submit();">
                    <i class="fa fa-save"></i> Gerar Consolidação
                  </button>
                </li>
                <li>
                  <button type="button" class="btn btn-danger" onclick="history.back();">
                    <i class="fa fa-arrow-left"></i> Voltar
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

              <div class="param-box">
                <h4 class="section-title"><i class="fa fa-calendar"></i> Período</h4>
                <div class="form-group">
                  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="data_inicial">Data Inicial <span class="badge-required">Obrigatório</span></label>
                  <div class="col-md-3 col-sm-6 col-xs-12">
                    <input id="data_inicial" name="data_inicial" type="text" required="required" class="form-control col-md-7 col-xs-12 datepicker" value="{$data_inicial}">
                  </div>
                  <label class="control-label col-md-2 col-sm-3 col-xs-12" for="data_final">Data Final <span class="badge-required">Obrigatório</span></label>
                  <div class="col-md-3 col-sm-6 col-xs-12">
                    <input id="data_final" name="data_final" type="text" required="required" class="form-control col-md-7 col-xs-12 datepicker" value="{$data_final}">
                  </div>
                </div>
              </div>

              <div class="info-card">
                <h4 class="section-title"><i class="fa fa-university"></i> Conta Bancária</h4>
                <div class="form-group">
                  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="conta">Conta <span class="badge-required">Obrigatório</span></label>
                  <div class="col-md-6 col-sm-6 col-xs-12">
                    <select id="conta" name="conta" class="form-control">
                      {html_options values=$conta_ids output=$conta_names selected=$conta_id}
                    </select>
                  </div>
                </div>
              </div>

            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>

{include file="template/form.inc"}

