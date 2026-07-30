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
    color: #38478b;
  }
  
  .x_title h2 i {
    color: #283468;
  }

  .section-title {
    font-size: 16px;
    font-weight: 600;
    color: #1f2d69;
    margin-bottom: 15px;
    padding-bottom: 10px;
    border-bottom: 2px solid #95a9fa;
    position: relative;
  }
  
  .section-title:before {
    content: '';
    position: absolute;
    bottom: -2px;
    left: 0;
    width: 5%;
    height: 2px;
    background: #1f2d69;
  }
  
  .section-title i {
    color: #2d3e8a;
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
    color: #1f2d69;
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
    background: linear-gradient(to bottom,rgb(243, 241, 241) 0%,rgb(216, 216, 216) 100%);
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 15px;
    border: 2px solid #e9e4ff;
    transition: all 0.3s;
  }
  .tributo-item:last-child {
    margin-bottom: 0;
  }

  .tributo-item label i {
    color: #1f2d69;
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
    color: #1f2d69;
  }
  
  .input-icon-right {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    font-weight: 600;
    color: #1f2d69;
  }

  .form-control.has-icon-right {
    padding-right: 35px;
  }
  
  .form-control:focus {
    border-color: #1f2d69;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
  }
  
  .info-card {
    background:rgb(255, 248, 248);
    border-radius: 8px;
    padding: 20px;
    border: 2px solid #e9e4ff;
    margin-bottom: 20px;
    box-shadow: 0 2px 10px rgba(102, 126, 234, 0.08);
  }

  /* Hover apenas para cards de credenciais de API (Bradesco / Inter) */
  .api-credential-card {
    transition: all 0.3s ease;
    cursor: default;
  }

  .api-credential-card:hover {
    box-shadow: 0 8px 20px rgba(102, 126, 234, 0.25);
    transform: translateY(-3px);
    filter: brightness(1.02);
  }

  .api-credential-card:hover h5 i {
    animation: pulseIcon 1s ease-in-out infinite;
  }

  @keyframes pulseIcon {
    0%, 100% { transform: scale(1); }
    50%      { transform: scale(1.15); }
  }
  
  .badge-required {
    background: linear-gradient(135deg, #1f2d69 0%, #5c6bbe 100%);
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
    border-color: #1f2d69;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
  }
  
  /* Select personalizado */
  select.form-control {
    border: 2px solid #e9e4ff;
    transition: all 0.3s;
  }
  
  select.form-control:focus {
    border-color: #1f2d69;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
  }
  
  /* Input personalizado */
  input.form-control {
    border: 2px solid #e9e4ff;
    transition: all 0.3s;
  }
  
  input.form-control:focus {
    border-color: #1f2d69;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
  }
  
  /* Panel personalizado */
  .x_panel {
    border-top: 4px solid #1f2d69;
    box-shadow: 0 4px 20px rgba(0, 35, 192, 0.1);
  }
  
  /* Labels com destaque roxo */
  .control-label {
    color: #414852;
    font-weight: 400;
    font-size: 14px;
  }
    /* Remove negrito dos labels do radio */
  .radio-group label {
      font-weight: normal !important;
  }

  /* Estilo do título de seção quando é um toggle de collapse */
  .section-title.collapse-toggle,
  .info-card h5.collapse-toggle {
      user-select: none;
      transition: color 0.2s ease;
      cursor: pointer;
  }
  .section-title.collapse-toggle:hover,
  .info-card h5.collapse-toggle:hover {
      opacity: 0.85;
  }
  .section-title.collapse-toggle .toggle-icon,
  .info-card h5.collapse-toggle .toggle-icon {
      transition: transform 0.3s ease;
      font-size: 14px;
      margin-top: 4px;
  }
  /* Quando colapsado: chevron apontando para baixo (estado padrão) */
  .section-title.collapse-toggle.collapsed .toggle-icon,
  .info-card h5.collapse-toggle.collapsed .toggle-icon {
      transform: rotate(0deg);
  }
  /* Quando expandido: chevron apontando para cima */
  .section-title.collapse-toggle:not(.collapsed) .toggle-icon,
  .info-card h5.collapse-toggle:not(.collapsed) .toggle-icon {
      transform: rotate(180deg);
  }
</style>

<script type="text/javascript" src="{$pathSweet}/dist/sweetalert2.all.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.0/jquery.mask.js"></script>
<script type="text/javascript" src="{$pathJs}/fin/s_fin.js"> </script>

<!-- page content -->
<div class="right_col" role="main">
  <div class="">

    <form id="lancamento" data-parsley-validate class="form-horizontal form-label-left" NAME="lancamento"
      ACTION="{$SCRIPT_NAME}" METHOD="post">
      <input name=id type=hidden value={$id}>
      <input name=mod type=hidden value="fin">
      <input name=form type=hidden value="banco">
      <input name=submenu type=hidden value={$subMenu}>
      <input name=letra type=hidden value={$letra}>
      <input name="inter_situacao_map_json" type="hidden" value="">
      <input name="bradesco_situacao_map_json" type="hidden" value="">


      <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
          <div class="x_panel">
            <div class="x_title">
              <h2>
                {if $subMenu eq "cadastrar"}
                  <i class="fa fa-plus-circle"></i> Contas Bancárias - Cadastro
                {else}
                  <i class="fa fa-edit"></i> Contas Bancárias - Alteração
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
                  <button type="button" class="btn btn-primary" onClick="javascript:submitConfirmar('conta_banco');">
                    <i class="fa fa-save"></i> Confirmar
                  </button>
                </li>
                <li>
                  <button type="button" class="btn btn-danger" onClick="javascript:submitVoltar('conta_banco');">
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

                <div class="form-group">
                  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nomeInterno">Nome Interno <span class="badge-required">Obrigatório</span></label>
                  <div class="col-md-6 col-sm-6 col-xs-12">
                    <input id="nomeInterno" name="nomeInterno" type="text" required="required"
                      class="form-control col-md-7 col-xs-12" maxlength="30"
                      placeholder="Nome que a conta é conhecida internamente na Empresa." value={$nomeInterno}>
                  </div>
                </div>

                <div class="form-group">
                  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nomeContaBanco">Nome Conta <span class="badge-required">Obrigatório</span></label>
                  <div class="col-md-6 col-sm-6 col-xs-12">
                    <input id="nomeContaBanco" name="nomeContaBanco" type="text" required="required"
                      class="form-control col-md-7 col-xs-12" maxlength="30" placeholder="Nome da conta no Banco."
                      value={$nomeContaBanco}>
                  </div>
                </div>

                <div class="form-group">
                  <label class="control-label col-md-3 col-sm-3 col-xs-12" for="banco">Banco <span class="badge-required">Obrigatório</span></label>
                  <div class="col-md-6 col-sm-6 col-xs-12">
                    <select class="form-control" name="banco" id="banco">
                      {html_options values=$banco_ids selected=$banco_id output=$banco_names}
                    </select>
                  </div>
                </div>
              </div>

              <!-- DADOS BANCÁRIOS -->
              <div class="info-card">
                <h4 class="section-title"><i class="fa fa-university"></i> Dados Bancários</h4>

                <div class="row">
                  <!-- STATUS -->
                  <div class="col-md-3">
                    <div class="tributo-item">
                      <label for="situacao" style="color: #374151; margin-bottom: 8px; display: block;">
                        <i class="fa fa-check-circle"></i> Status
                      </label>
                      <div class="input-with-icon">
                        <select class="form-control" name="situacao" id="situacao">
                          {html_options values=$situacao_ids output=$situacao_names selected=$situacao_id}
                        </select>
                      </div>
                    </div>
                  </div>

                  <!-- AGÊNCIA -->
                  <div class="col-md-2">
                    <div class="tributo-item">
                      <label for="agencia" style="color: #374151; margin-bottom: 8px; display: block;">
                        <i class="fa fa-building-o"></i> Agência
                      </label>
                      <div class="input-with-icon">
                        <input id="agencia" name="agencia" type="number" required="required"
                          class="form-control" maxlength="6"
                          title="Digite o código da agência sem o digito verificador."
                          onKeyPress="if(this.value.length==6) return false;" value={$agencia}>
                      </div>
                    </div>
                  </div>

                  <!-- CONTA -->
                  <div class="col-md-2">
                    <div class="tributo-item">
                      <label for="contaCorrente" style="color: #374151; margin-bottom: 8px; display: block;">
                        <i class="fa fa-credit-card-alt"></i> Conta
                      </label>
                      <div class="input-with-icon">
                        <input id="contaCorrente" name="contaCorrente" type="text" required="required"
                          class="form-control" maxlength="15"
                          title="Número da conta corrente sem o dígito verificador."
                          placeholder="Somente números, sem o dígito" value={$contaCorrente}>
                      </div>
                    </div>
                  </div>
                  <!-- DÍGITO -->
                  <div class="col-md-2">
                    <div class="tributo-item">
                      <label for="contaCorrenteDigito" style="color: #374151; margin-bottom: 8px; display: block;">
                        <i class="fa fa-key"></i> Dígito <!--<small style="color:#6b7280;">(apenas banco inter)</small> -->
                      </label>
                      <div class="input-with-icon">
                        <input id="contaCorrenteDigito" name="contaCorrenteDigito" type="text"
                          class="form-control text-center" maxlength="1" style="text-transform: uppercase;"
                          title="Dígito verificador da conta (1 caractere: 0-9 ou X)." placeholder=""
                          value={$contaCorrenteDigito}>
                      </div>
                    </div>
                  </div>
                  <!-- CONTATO -->
                  <div class="col-md-3">
                    <div class="tributo-item">
                      <label for="contato" style="color: #374151; margin-bottom: 8px; display: block;">
                        <i class="fa fa-user-circle-o"></i> Contato
                      </label>
                      <div class="input-with-icon">
                        <input id="contato" name="contato" type="text" required="required"
                          class="form-control" maxlength="15"
                          title="Nome do responsável ou setor no banco."
                          placeholder="Nome do contato no banco." value={$contato}>
                      </div>
                    </div>
                  </div>

                </div>

                <div class="row" style="margin-top: 15px;">

                  <!-- ÚLTIMO NOSSO NÚMERO -->
                  <div class="col-md-3">
                    <div class="tributo-item">
                      <label for="UltimoNossoNro" style="color: #374151; margin-bottom: 8px; display: block;">
                        <i class="fa fa-hashtag"></i> Último Nosso Número
                      </label>
                      <div class="">
                        <input id="UltimoNossoNro" name="UltimoNossoNro" type="text" readonly
                          class="form-control" value={$UltimoNossoNro}>
                      </div>
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="tributo-item">
                      <label for="envia_boleto" style="color: #374151; margin-bottom: 17px; display: block;">
                        <i class="fa fa-paper-plane"></i> Envio de boletos
                      </label>
                      <div class="radio-group" style="gap: 4em;">
                        {html_radios class="flat" name="envia_boleto" values=['A','R'] output=['API','Remessa bancária'] selected=$envia_boleto separator=" "}
                      </div>
                    </div>
                  </div>

                  <div class="col-md-5">
                    <div class="tributo-item">
                      <label for="ambiente" style="color: #374151; margin-bottom: 17px; display: block;">
                        <i class="fa fa-cogs"></i> Ambiente de uso da API
                      </label>
                      <div class="radio-group" style="gap: 4em;">
                        {html_radios class="flat" name="ambiente" values=['S','P'] output=['Sandbox','Produção'] selected=$ambiente separator=" "}
                      </div>
                    </div>
                  </div>
                </div>

              </div>

              <!-- CONFIGURAÇÕES DE COBRANÇA -->
              <div class="tributos-box">
                <h4 class="section-title"><i class="fa fa-file-text-o"></i> Configurações de Cobrança</h4>

                <div class="row">
                  <div class="col-md-4">
                    <div class="tributo-item">
                      <label style="color: #374151; margin-bottom: 8px; display: block;">
                        <i class="fa fa-star"></i> Desconto
                      </label>
                      <div class="input-with-icon">
                        <input id="descontoBonificacao" name="descontoBonificacao" type="text" required="required"
                          class="form-control has-icon-right money"
                          placeholder="Alíquota para pagamento antes do vencimento" value={$descontoBonificacao}>
                        <span class="input-icon-right">%</span>
                      </div>
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="tributo-item">
                      <label style="color: #374151; margin-bottom: 8px; display: block;">
                        <i class="fa fa-exclamation-triangle"></i> Multa
                      </label>
                      <div class="input-with-icon">
                        <input id="multa" name="multa" type="text" required="required"
                          class="form-control has-icon-right money" value={$multa}>
                        <span class="input-icon-right">%</span>
                      </div>
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="tributo-item">
                      <label style="color: #374151; margin-bottom: 8px; display: block;">
                        <i class="fa fa-calendar"></i> Juros
                      </label>
                      <div class="input-with-icon">
                        <input id="juros" name="juros" type="text" required="required"
                          class="form-control has-icon-right money" value={$juros}>
                        <span class="input-icon-right">%</span>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="row" style="margin-top: 15px;">
                  <div class="col-md-4">
                    <div class="tributo-item">
                      <label style="color: #374151; margin-bottom: 8px; display: block;">
                        <i class="fa fa-barcode"></i> Número Cobrança Banco
                      </label>
                      <div class="input-with-icon">
                        <input id="numNoBanco" name="numNoBanco" type="text" required="required"
                          class="form-control" maxlength="20"
                          placeholder="Digite o numero de identificação de cobrança no Banco." value={$numNoBanco}>
                      </div>
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="tributo-item">
                      <label style="color: #374151; margin-bottom: 8px; display: block;">
                        <i class="fa fa-folder-open"></i> Carteira de Cobrança
                      </label>
                      <div class="input-with-icon">
                        <input id="carteiraCobranca" name="carteiraCobranca" type="text" required="required"
                          class="form-control" maxlength="4" value={$carteiraCobranca}>
                      </div>
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="tributo-item">
                      <label style="color: #374151; margin-bottom: 8px; display: block;">
                        <i class="fa fa-gavel"></i> Dia(s) Protesto
                      </label>
                      <div class="input-with-icon">
                        <input id="diaProtesto" name="diaProtesto" type="text" required="required"
                          class="form-control has-icon-right" maxlength="6" value={$diaProtesto}>
                        <span class="input-icon-right">dias</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- MENSAGEM DO BOLETO -->
              <div class="info-card">
                <h4 class="section-title"><i class="fa fa-comment"></i> Mensagem do Boleto</h4>
                <div class="form-group">
                  <div class="col-md-12 col-sm-12 col-xs-12">
                    <textarea class="resizable_textarea form-control col-md-6 col-xs-12" id="msgBoleto" name="msgBoleto"
                      rows="3" placeholder="Digite a mensagem que aparecerá no boleto...">{$msgBoleto}</textarea>
                  </div>
                </div>
              </div>

              <!-- CREDENCIAIS DE API BRREDESCO-->
              <div class="param-box">
                <h4 class="section-title collapse-toggle collapsed" data-toggle="collapse" data-target="#collapseCredBradesco"
                  aria-expanded="false" aria-controls="collapseCredBradesco" role="button" style="cursor: pointer;">
                  <i class="fa fa-key"></i> Credenciais de API - &nbsp;
                  <img src="{$pathImagem}/logobradesco.jpg" width="130" height="34">
                  <i class="fa fa-chevron-down pull-right toggle-icon"></i>
                </h4>

                <div id="collapseCredBradesco" class="collapse" aria-labelledby="headingCredBradesco">

                  <!-- MAPEAMENTO DE SITUAÇÕES -->
                  <div class="info-card" style="background: linear-gradient(to bottom, #eff6ff 0%, #ffffff 100%); border-color: #93c5fd; margin-top: 20px;">
                    <h5 class="collapse-toggle collapsed" data-toggle="collapse" data-target="#collapseBradescoSituacaoMap"
                      aria-expanded="false" aria-controls="collapseBradescoSituacaoMap" role="button"
                      style="color: #1d4ed8; font-weight: 600; margin-bottom: 0;">
                      <i class="fa fa-exchange"></i> Mapeamento de Situações
                      <i class="fa fa-chevron-down pull-right toggle-icon"></i>
                    </h5>

                    <div id="collapseBradescoSituacaoMap" class="collapse" aria-labelledby="headingBradescoSituacaoMap">
                      <p style="color: #64748b; margin: 15px 0 20px; font-size: 13px;">
                        Configure qual situação do sistema será utilizada quando a API do Banco Bradesco retornar cada status de cobrança.
                      </p>

                      {foreach from=$bradesco_situacoes item=bradesco_situacao}
                      <div class="form-group">
                        <label class="control-label col-md-5 col-sm-5 col-xs-12" for="bradesco_situacao_map_{$bradesco_situacao.id}" style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                          <span class="label label-primary" style="flex-shrink: 0; font-size: 11px; font-weight: 500; letter-spacing: 0.3px;">{$bradesco_situacao.id}</span>
                          <span style="font-weight: normal; color: #64748b; font-size: 12px; line-height: 1.3;">{$bradesco_situacao.label}</span>
                        </label>
                        <div class="col-md-4 col-sm-4 col-xs-12">
                          <select class="form-control" data-bradesco-situacao-map="{$bradesco_situacao.id}" id="bradesco_situacao_map_{$bradesco_situacao.id}">
                            {html_options values=$situacaoLanc_ids output=$situacaoLanc_names selected=$bradesco_situacao_map[$bradesco_situacao.id]}
                          </select>
                        </div>
                      </div>
                      {/foreach}
                    </div>
                  </div>

                  <!-- AMBIENTE PRODUÇÃO -->
                  <div class="info-card" style="background: linear-gradient(to bottom, #dcfded 0%, #ffffff 100%); border-color: #6ee7b7; margin-top: 20px;">
                    <h5 style="color: #048a5f; font-weight: 600; margin-bottom: 15px;">
                      <i class="fa fa-rocket"></i> Ambiente Produção
                    </h5>
                    
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="prodClientId">
                        Client Identificador 
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="bradesco_api_client_id_production" 
                          name="bradesco_api_client_id_production" 
                          type="text"
                          class="form-control col-md-7 col-xs-12" maxlength="100"
                          placeholder="Digite o Client ID do ambiente Produção" value="{$bradesco_api_client_id_production}">
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="prodClientSecret">
                        Client Secret 
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="input-group">
                          <input id="bradesco_api_client_secret_production" 
                            name="bradesco_api_client_secret_production" 
                            type="password"
                            class="form-control col-md-7 col-xs-12"
                            placeholder="Digite o Client Secret do ambiente Produção" 
                            value="{$bradesco_api_client_secret_production}">
                          <span class="input-group-btn">
                            <button class="btn btn-default" type="button" onclick="togglePassword('bradesco_api_client_secret_production')">
                              <i class="fa fa-eye"></i>
                            </button>
                          </span>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- AMBIENTE SANDBOX -->
                  <div class="info-card" style="background: linear-gradient(to bottom, #fffbeb 0%, #ffffff 100%); border-color: #fcd34d;">
                    <h5 style="color: #d97706; font-weight: 600; margin-bottom: 15px;">
                      <i class="fa fa-flask"></i> Ambiente Sandbox (Testes)
                    </h5>
                    
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="sandboxClientId">
                        Client Identificador 
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="bradesco_api_client_id_sandbox" 
                          name="bradesco_api_client_id_sandbox" 
                          type="text"
                          class="form-control col-md-7 col-xs-12" maxlength="100"
                          placeholder="Digite o Client ID do ambiente Sandbox" value="{$bradesco_api_client_id_sandbox}">
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="sandboxClientSecret">
                        Client Secret 
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="input-group">
                          <input id="bradesco_api_client_secret_sandbox" 
                            name="bradesco_api_client_secret_sandbox" 
                            type="password"
                            class="form-control col-md-7 col-xs-12" maxlength="200"
                            placeholder="Digite o Client Secret do ambiente Sandbox" value="{$bradesco_api_client_secret_sandbox}">
                          <span class="input-group-btn">
                            <button class="btn btn-default" type="button" onclick="togglePassword('bradesco_api_client_secret_sandbox')">
                              <i class="fa fa-eye"></i>
                            </button>
                          </span>
                        </div>
                      </div>
                    </div>
                  </div>

                </div>
              </div>

              <!-- CREDENCIAIS DE API INTER -->
              <div class="param-box">
                <h4 class="section-title collapse-toggle collapsed" data-toggle="collapse" data-target="#collapseCredInter"
                  aria-expanded="false" aria-controls="collapseCredInter" role="button" style="cursor: pointer;">
                  <i class="fa fa-key"></i> Credenciais de API - &nbsp;
                  <img src="{$pathImagem}/logointer.png" width="100" height="22">
                  <i class="fa fa-chevron-down pull-right toggle-icon"></i>
                </h4>

                <div id="collapseCredInter" class="collapse" aria-labelledby="headingCredInter">

                  <!-- MAPEAMENTO DE SITUAÇÕES -->
                  <div class="info-card" style="background: linear-gradient(to bottom, #eff6ff 0%, #ffffff 100%); border-color: #93c5fd; margin-top: 20px;">
                    <h5 class="collapse-toggle collapsed" data-toggle="collapse" data-target="#collapseInterSituacaoMap"
                      aria-expanded="false" aria-controls="collapseInterSituacaoMap" role="button"
                      style="color: #1d4ed8; font-weight: 600; margin-bottom: 0;">
                      <i class="fa fa-exchange"></i> Mapeamento de Situações
                      <i class="fa fa-chevron-down pull-right toggle-icon"></i>
                    </h5>

                    <div id="collapseInterSituacaoMap" class="collapse" aria-labelledby="headingInterSituacaoMap">
                      <p style="color: #64748b; margin: 15px 0 20px; font-size: 13px;">
                        Configure qual situação do sistema será utilizada quando a API do Banco Inter retornar cada status de cobrança.
                      </p>

                      {foreach from=$inter_situacoes item=inter_situacao}
                      <div class="form-group">
                        <label class="control-label col-md-4 col-sm-4 col-xs-12" for="inter_situacao_map_{$inter_situacao.id}" style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                          <span class="label label-primary" style="flex-shrink: 0; font-size: 11px; font-weight: 500; letter-spacing: 0.3px;">{$inter_situacao.id}</span>
                          <span style="font-weight: normal; color: #64748b; font-size: 12px; line-height: 1.3;">{$inter_situacao.label}</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <select class="form-control" data-inter-situacao-map="{$inter_situacao.id}" id="inter_situacao_map_{$inter_situacao.id}">
                            {html_options values=$situacaoLanc_ids output=$situacaoLanc_names selected=$inter_situacao_map[$inter_situacao.id]}
                          </select>
                        </div>
                      </div>
                      {/foreach}
                    </div>
                  </div>

                  <!-- AMBIENTE PRODUÇÃO -->
                  <div class="info-card" style="background: linear-gradient(to bottom, #dcfded 0%, #ffffff 100%); border-color: #6ee7b7; margin-top: 20px;">
                    <h5 style="color: #048a5f; font-weight: 600; margin-bottom: 15px;">
                      <i class="fa fa-rocket"></i> Ambiente Produção
                    </h5>
                    
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="prodClientId">
                        Client Identificador 
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="inter_api_client_id_production" 
                          name="inter_api_client_id_production" 
                          type="text"
                          class="form-control col-md-7 col-xs-12" maxlength="100"
                          placeholder="Digite o Client ID do ambiente Produção" value="{$inter_api_client_id_production}">
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="prodClientSecret">
                        Client Secret 
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="input-group">
                          <input id="inter_api_client_secret_production" 
                            name="inter_api_client_secret_production" 
                            type="password"
                            class="form-control col-md-7 col-xs-12"
                            placeholder="Digite o Client Secret do ambiente Produção" 
                            value="{$inter_api_client_secret_production}">
                          <span class="input-group-btn">
                            <button class="btn btn-default" type="button" onclick="togglePassword('inter_api_client_secret_production')">
                              <i class="fa fa-eye"></i>
                            </button>
                          </span>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- AMBIENTE SANDBOX -->
                  <div class="info-card" style="background: linear-gradient(to bottom, #fffbeb 0%, #ffffff 100%); border-color: #fcd34d;">
                    <h5 style="color: #d97706; font-weight: 600; margin-bottom: 15px;">
                      <i class="fa fa-flask"></i> Ambiente Sandbox (Testes)
                    </h5>
                    
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="sandboxClientId">
                        Client Identificador 
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="inter_api_client_id_sandbox" 
                          name="inter_api_client_id_sandbox" 
                          type="text"
                          class="form-control col-md-7 col-xs-12" maxlength="100"
                          placeholder="Digite o Client ID do ambiente Sandbox" value="{$inter_api_client_id_sandbox}">
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12" for="interSandboxClientSecret">
                        Client Secret 
                      </label>
                      <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="input-group">
                          <input id="inter_api_client_secret_sandbox" 
                            name="inter_api_client_secret_sandbox" 
                            type="password"
                            class="form-control col-md-7 col-xs-12" maxlength="200"
                            placeholder="Digite o Client Secret do ambiente Sandbox" value="{$inter_api_client_secret_sandbox}">
                          <span class="input-group-btn">
                            <button class="btn btn-default" type="button" onclick="togglePassword('inter_api_client_secret_sandbox')">
                              <i class="fa fa-eye"></i>
                            </button>
                          </span>
                        </div>
                      </div>
                    </div>
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

<script>
  function togglePassword(fieldId) {
    var field = document.getElementById(fieldId);
    var button = event.currentTarget;
    var icon = button.querySelector('i');
    
    if (field.type === "password") {
      field.type = "text";
      icon.classList.remove('fa-eye');
      icon.classList.add('fa-eye-slash');
    } else {
      field.type = "password";
      icon.classList.remove('fa-eye-slash');
      icon.classList.add('fa-eye');
    }
  }
</script>

<script>
  $('.money').mask('000.000.000.000.000,00', { reverse: true });
  $(".money").change(function() {
    $("#value").html($(this).val().replace(/\D/g, ''))
  })
  $('.money').on('keyUp', function() {
    if ($(this).val().length > 3) {
      mascara = '####00,00';
    } else {
      mascara = '####0,00';
    }

    $('.money').mask(mascara, { reverse: true });
  });
</script>