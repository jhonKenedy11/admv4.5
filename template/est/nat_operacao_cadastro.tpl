<style>
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
<script type="text/javascript" src="{$pathJs}/est/s_est.js"></script>

<!-- page content -->
<div class="right_col" role="main">
  <div class="">
    <div class="clearfix"></div>

    <form id="lancamento" data-parsley-validate class="form-horizontal form-label-left" NAME="lancamento"
      ACTION="{$SCRIPT_NAME}" METHOD="post">
      <input name=mod type=hidden value="est">
      <input name=form type=hidden value="nat_operacao">
      <input name=submenu type=hidden value={$subMenu}>
      <input name=letra type=hidden value={$letra}>
      <input name=id type=hidden value={$id}>
      <input name=idNatop type=hidden value={$idNatop}>

      <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
          <div class="x_panel">
            <div class="x_title">
              <h2>
                {if $subMenu eq "cadastrar"}
                  <i class="fa fa-plus-circle"></i> Natureza de Operação - Cadastro
                {else}
                  <i class="fa fa-edit"></i> Natureza de Operação - Alteração
                {/if}
              </h2>

              {if $mensagem neq ''}
                {if $tipoMsg eq 'sucesso'}
                  <div class="alert alert-success" role="alert" style="margin-top: 15px;">
                    <strong><i class="fa fa-check-circle"></i> Sucesso!</strong> {$mensagem}
                  </div>
                {elseif $tipoMsg eq 'alerta'}
                  <div class="alert alert-danger" role="alert" style="margin-top: 15px;">
                    <strong><i class="fa fa-exclamation-triangle"></i> Aviso!</strong> {$mensagem}
                  </div>
                {/if}
              {/if}

              <ul class="nav navbar-right panel_toolbox">
                <li>
                  <button type="button" class="btn btn-primary" onClick="javascript:submitConfirmar('nat_operacao');">
                    <i class="fa fa-save"></i> Confirmar
                  </button>
                </li>
                <li>
                  <button type="button" class="btn btn-danger" onClick="javascript:submitVoltar('nat_operacao');">
                    <i class="fa fa-arrow-left"></i> Voltar
                  </button>
                </li>
              </ul>
              <div class="clearfix"></div>
            </div>

            <div class="x_content">
              
              <!-- INFORMAÇÕES BÁSICAS -->
              <div class="info-card">
                <h4 class="section-title"><i class="fa fa-info-circle"></i> Informações Básicas</h4>

                <div class="row">

                  <div class="form-col">
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <label for="natOperacao">Natureza Operação <span class="badge-required">Obrigatório</span></label>
                      <input class="form-control"  id="natOperacao" name="natOperacao" type="text" required="required"
                        placeholder="Digite a descrição da natureza de operação" value={$natOperacao}>
                    </div>

                  </div>

                  <div class="form-col">
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <label for="tipo">Tipo <span class="badge-required">Obrigatório</span></label>
                      <select class="form-control" name="tipo" id="tipo">
                        {html_options values=$tipoNatOp_ids selected=$tipoNatOp_id output=$tipoNatOp_names}
                      </select>
                    </div>
                  </div>

                </div>

                </br>

                <div class="row">
                  <div class="form-col">
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <label for="modeloNf">Modelo NF <span class="badge-required">Obrigatório</span></label>
                      <input class="form-control" id="modeloNf" name="modeloNf" type="text" required="required"
                        placeholder="Ex: 55, 65" value={$modeloNf}>
                    </div>
                  </div>

                  <div class="form-col">
                    <div class="col-md-6 col-sm-6 col-xs-12">
                      <label for="codFiscOrigem">Código Fiscal Origem</label>
                      <input class="form-control" id="codFiscOrigem" name="codFiscOrigem" type="text"
                        placeholder="Digite o código fiscal de origem" value={$codFiscOrigem}>
                    </div>
                  </div>
                </div>
              </div>


              <!-- PARÂMETROS DO SISTEMA -->
              <div class="param-box">
                <h4 class="section-title"><i class="fa fa-cogs"></i> Parâmetros do Sistema</h4>
                
                <div class="row">
                  <div class="col-md-6">
                    <div class="param-item">
                      <label><i class="fa fa-dollar"></i> Altera Preços</label>
                      <div class="radio-group">
                        {html_radios class="flat" name="alteraPrecos" values=$boolean_ids output=$boolean_names selected=$alteraPrecos separator=" "}
                      </div>
                    </div>
                  </div>
                  
                  <div class="col-md-6">
                    <div class="param-item">
                      <label><i class="fa fa-sort-numeric-asc"></i> Altera Quantidade</label>
                      <div class="radio-group">
                        {html_radios class="flat" name="alteraQuant" values=$boolean_ids output=$boolean_names selected=$alteraQuant separator=""}
                      </div>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-6">
                    <div class="param-item">
                      <label><i class="fa fa-money"></i> Integra Financeiro</label>
                      <div class="radio-group">
                        {html_radios class="flat" name="integraFin" values=$boolean_ids output=$boolean_names selected=$integraFin separator=""}
                      </div>
                    </div>
                  </div>
                  
                  <div class="col-md-6">
                    <div class="param-item">
                      <label><i class="fa fa-calculator"></i> Posição Tributos</label>
                      <div class="radio-group">
                        {html_radios class="flat" name="posicaoTributos" values=$boolean_ids output=$boolean_names selected=$posicaoTributos separator=""}
                      </div>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-6">
                    <div class="param-item">
                      <label><i class="fa fa-file-text"></i> NF Automática</label>
                      <div class="radio-group">
                        {html_radios class="flat" name="nfAuto" values=$boolean_ids output=$boolean_names selected=$nfAuto separator=""}
                      </div>
                    </div>
                  </div>
                  
                  <div class="col-md-6">
                    <div class="param-item">
                      <label><i class="fa fa-percent"></i> Retém IRRF
                        <!-- ícone de ajuda tipo botão -->
                        <button type="button" class="btn btn-xs btn-link" onClick="helpIRRF()" style="margin-left:5px; color: #667eea;">
                          <i class="fa fa-question"></i>
                        </button>
                      </label>
                  
                      <div class="radio-group">
                        {html_radios class="flat" name="irrf" values=$boolean_ids output=$boolean_names selected=$irrf separator=""}
                      </div>
                    </div>
                  </div>

                </div>
              </div>

              <!-- CONFIGURAÇÕES TRIBUTÁRIAS -->
              <div class="tributos-box">
              <h4 class="section-title"><i class="fa fa-university"></i> Configurações Tributárias</h4>
                
                <div class="row">
                  <div class="col-md-4">
                    <div class="tributo-item">
                      <label style="color: #374151; margin-bottom: 8px; display: block;">
                        <i class="fa fa-building"></i> Simples Federal
                      </label>
                      <div class="input-with-icon">
                        <input id="tribSimples" name="tribSimples" type="text" 
                          class="form-control has-icon-left money" maxlength="5"
                          placeholder="0,00" value={$tribSimples}>
                          <span class="input-icon-right">%</span>
                      </div>
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="tributo-item">
                      <label style="color: #374151; margin-bottom: 8px; display: block;">
                        <i class="fa fa-credit-card"></i> Crédito Simples
                      </label>
                      <div class="input-with-icon">
                        <input id="percCreditoSimples" name="percCreditoSimples" type="text"
                          class="form-control has-icon-right money" maxlength="5"
                          placeholder="0,00" value={$percCreditoSimples}>
                        <span class="input-icon-right">%</span>
                      </div>
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="tributo-item">
                      <label style="color: #374151; margin-bottom: 8px; display: block;">
                        <i class="fa fa-bank"></i> Percentual IRRF
                      </label>
                      <div class="input-with-icon">
                        <input id="percIRRF" name="percIRRF" type="text"
                          class="form-control has-icon-right money" maxlength="5"
                          placeholder="0,00" value={$percIRRF}>
                        <span class="input-icon-right">%</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- OBSERVAÇÕES -->
              <div class="info-card">
                <h4 class="section-title"><i class="fa fa-comment"></i> Observações da Nota</h4>
                <div class="form-group">
                  <div class="col-md-12 col-sm-12 col-xs-12">
                    <textarea class="form-control" id="obs" name="obs" rows="3" 
                      placeholder="Digite observações que aparecerão na nota fiscal...">{$obs}</textarea>
                  </div>
                </div>
              </div>

              <div class="ln_solid"></div>

            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>

{include file="template/form.inc"}

<script src="https://cdn.rawgit.com/plentz/jquery-maskmoney/master/dist/jquery.maskMoney.min.js"></script>
<script>
  $(document).ready(function() {
    $(".money").maskMoney({
      decimal: ",",
      thousands: ".",
      precision: 2,
      allowZero: true
    });
  });
</script>