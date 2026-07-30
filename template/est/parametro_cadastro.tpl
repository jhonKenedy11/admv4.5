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
    font-weight: normal !important;
  }

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
    padding: 12px 15px;
    background: white;
    border-radius: 6px;
    margin-bottom: 10px;
    border: 1px solid #e9e4ff;
    transition: all 0.3s;
  }

  .param-item:last-child {
    margin-bottom: 0;
  }

  .param-item:hover {
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.2);
    border-color: #667eea;
    transform: translateY(-2px);
  }

  .param-item .param-label {
    margin: 0;
    font-weight: 500;
    color: #4b5563;
    flex: 1;
  }

  .param-item .param-label i {
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
    background: linear-gradient(to bottom, rgb(243, 241, 241) 0%, rgb(216, 216, 216) 100%);
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

  .form-control:focus,
  .form-control:focus-within {
    border-color: #1f2d69;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
  }

  .info-card {
    background: rgb(255, 248, 248);
    border-radius: 8px;
    padding: 20px;
    border: 2px solid #e9e4ff;
    margin-bottom: 20px;
    box-shadow: 0 2px 10px rgba(102, 126, 234, 0.08);
  }

  select.form-control {
    border: 2px solid #e9e4ff;
    transition: all 0.3s;
  }

  select.form-control:focus {
    border-color: #1f2d69;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
  }

  input.form-control,
  textarea.form-control {
    border: 2px solid #e9e4ff;
    transition: all 0.3s;
  }

  input.form-control:focus,
  textarea.form-control:focus {
    border-color: #1f2d69;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
  }

  .x_panel {
    border-top: 4px solid #1f2d69;
    box-shadow: 0 4px 20px rgba(0, 35, 192, 0.1);
  }

  .control-label {
    color: #414852;
    font-weight: 400;
    font-size: 14px;
  }

  .swal-modal {
    width: 550px !important;
  }

  .param-note {
    font-size: 12px;
    color: #6b7280;
    margin-top: 6px;
  }

  table {
    border-spacing: 0;
    border-collapse: none !important;
  }

  .table-bordered>thead>tr>th {
    border-radius: 7px !important;
    padding: 5px !important;
  }

  .x_panel,
  [name=datatable-buttons_length],
  [type=search] {
    border-radius: 5px;
  }

  .right_col { padding-left: 5px !important; padding-right: 5px !important; }
  .x_panel { padding-top: 5px !important; }

  .param-tip {
    color: #9ca3af;
    margin-left: 4px;
    cursor: help;
    font-size: 13px;
    vertical-align: middle;
  }
  .param-tip:hover { color: #1f2d69; }
</style>

{* Template para cadastro/alteração dos parâmetros de estoque *}
{* Arquivo: template/est/parametro_cadastro.tpl *}

<script type="text/javascript" src="{$pathSweet}/dist/sweetalert2.all.min.js"></script>
<script type="text/javascript" src="{$pathJs}/est/s_parametro.js"></script>

<div class="right_col" role="main">
    <div class="">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>
                            {if $submenu == 'alterar'}
                                <i class="fa fa-edit"></i> Alteração de Parâmetros de Estoque
                            {else}
                                <i class="fa fa-plus"></i> Cadastro de Parâmetros de Estoque
                            {/if}
                        </h2>
                        
                        <ul class="nav navbar-right panel_toolbox">
                            {if $submenu == 'alterar'}
                                {* Botões para modo alteração *}
                                <li>
                                    <button type="button" class="btn btn-primary btn-sm" onclick="submitConfirmar();">
                                        <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span>
                                        Salvar Alterações
                                    </button>
                                </li>
                                <li>
                                    <button type="button" class="btn btn-danger btn-sm" onclick="voltarListagem()">
                                        <span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span>
                                        Voltar
                                    </button>
                                </li>
                            {else}
                                {* Botões para modo cadastro *}
                                <li>
                                    <button type="button" class="btn btn-primary btn-sm" onclick="submitConfirmar();">
                                        <span class="glyphicon glyphicon-floppy-save" aria-hidden="true"></span>
                                        Cadastrar
                                    </button>
                                </li>
                                <li>
                                    <button type="button" class="btn btn-warning btn-sm" onclick="limparFormulario()">
                                        <span class="glyphicon glyphicon-refresh" aria-hidden="true"></span>
                                        Limpar
                                    </button>
                                </li>
                                <li>
                                    <button type="button" class="btn btn-danger btn-sm" onclick="voltarListagem()">
                                        <span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span>
                                        Voltar
                                    </button>
                                </li>
                            {/if}
                        </ul>
                        
                        <div class="clearfix"></div>
                    </div>
                    
                    <div class="x_content">
                        <form class="full" id="formParametros" name="parametro" method="post" ACTION={$SCRIPT_NAME}>
                            <input name=mod type=hidden value="est">
                            <input name=form type=hidden value="parametro">
                            <input name=submenu type=hidden value="{$submenu}">
                            
                            {* Seção 1: Identificação *}
                            <div class="row">
                                <div class="col-md-12">
                                    <h4><i class="fa fa-building"></i> Identificação</h4>
                                    <hr>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="filial">Empresa (centro de custo) <span class="text-danger">*</span> <i class="fa fa-info-circle param-tip" data-toggle="tooltip" title="Empresa vinculada ao centro de custo (AMB_EMPRESA.CENTROCUSTO). Cada combinação empresa + modelo permite um conjunto de parâmetros."></i></label>
                                        <select class="form-control" id="filial" {if $submenu != 'alterar'}name="filial"{/if} required 
                                                {if $submenu == 'alterar'} disabled {/if}>
                                            <option value="">Selecione uma empresa...</option>
                                            {html_options values=$empresas_ids output=$empresas_names selected=$empresa_id}
                                        </select>
                                        {if $submenu == 'alterar'}
                                        <input type="hidden" name="filial" value="{$dados.FILIAL}">
                                        {/if}
                                    </div>
                                </div>  

                                <!-- Modelo -->
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="modelo">Modelo <span class="text-danger">*</span> <i class="fa fa-info-circle param-tip" data-toggle="tooltip" title="Tipo do documento fiscal: NFe, NFCe ou CTe."></i></label>
                                        <select class="form-control" id="modelo" {if $submenu != 'alterar'}name="modelo"{/if} required
                                                {if $submenu == 'alterar'} disabled {/if}>
                                            <option value="55" {if $dados.MODELO == '55' || !$dados.MODELO}selected{/if}>55 - NFe</option>
                                            <option value="65" {if $dados.MODELO == '65'}selected{/if}>65 - NFCe</option>
                                            <option value="57" {if $dados.MODELO == '57'}selected{/if}>57 - CTe</option>
                                        </select>
                                        {if $submenu == 'alterar'}
                                        <input type="hidden" name="modelo" value="{$dados.MODELO}">
                                        {/if}
                                    </div>
                                </div>

                                <!-- Série -->
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="serie">Série <i class="fa fa-info-circle param-tip" data-toggle="tooltip" title="Número da série usada na emissão de notas."></i></label>
                                        <input type="text" class="form-control" maxlength="3" id="serie" name="serie" 
                                            value="{$dados.SERIE}" maxlength="3">
                                    </div>
                                </div>

                            </div>
                            
                            {* Seção 2: Configurações Fiscais *}
                            <div class="row">
                                <div class="col-md-12">
                                    <h4><i class="fa fa-file-text"></i> Configurações Fiscais</h4>
                                    <hr>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="cfop">CFOP <i class="fa fa-info-circle param-tip" data-toggle="tooltip" title="CFOP padrão nas operações de estoque."></i></label>
                                        <input type="text" class="form-control" id="cfop" name="cfop" 
                                               value="{$dados.CFOP}" maxlength="15">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="natoperacao">Natureza da Operação <i class="fa fa-info-circle param-tip" data-toggle="tooltip" title="Natureza de operação fiscal padrão na saída."></i></label>
                                        <input type="text" class="form-control" id="natoperacao" name="natoperacao" 
                                               value="{$dados.NATOPERACAO}" maxlength="20">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="natopentrada">Natureza Op. Entrada <i class="fa fa-info-circle param-tip" data-toggle="tooltip" title="Natureza de operação usada nas entradas de mercadoria."></i></label>
                                        <input type="number" class="form-control" id="natopentrada" name="natopentrada" 
                                               value="{$dados.NATOPENTRADA}">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="generomovimento">Gênero Movimento <i class="fa fa-info-circle param-tip" data-toggle="tooltip" title="Gênero contábil dos movimentos de estoque."></i></label>
                                        <input type="text" class="form-control" id="generomovimento" name="generomovimento" 
                                               value="{$dados.GENEROMOVIMENTO}" maxlength="4">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="modofin">Modo Financeiro <i class="fa fa-info-circle param-tip" data-toggle="tooltip" title="Define se o financeiro será à vista ou a prazo."></i></label>
                                        <select class="form-control" id="modofin" name="modofin">
                                            <option value="">Selecione...</option>
                                            <option value="A" {if $dados.MODOFIN == 'A'}selected{/if}>A - À Vista</option>
                                            <option value="P" {if $dados.MODOFIN == 'P'}selected{/if}>P - À Prazo</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="tipodoc">Tipo Documento <i class="fa fa-info-circle param-tip" data-toggle="tooltip" title="Documento padrão: entrada ou saída."></i></label>
                                        <select class="form-control" id="tipodoc" name="tipodoc">
                                            <option value="">Selecione...</option>
                                            <option value="E" {if $dados.TIPODOC == 'E'}selected{/if}>E - Entrada</option>
                                            <option value="S" {if $dados.TIPODOC == 'S'}selected{/if}>S - Saída</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            {* Seção 3: Configurações Financeiras *}
                            <div class="row">
                                <div class="col-md-12">
                                    <h4><i class="fa fa-money"></i> Configurações Financeiras</h4>
                                    <hr>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="condpgto">Condição de Pagamento <i class="fa fa-info-circle param-tip" data-toggle="tooltip" title="Condição de pagamento padrão nas notas."></i></label>
                                        {assign var="condpgto_ids" value=array()}
                                        {assign var="condpgto_names" value=array()}
                                        {if $condicoes_pagamento}
                                            {foreach from=$condicoes_pagamento item=cond}
                                                {assign var="condpgto_ids" value=$condpgto_ids|@array_merge:array($cond.ID)}
                                                {assign var="condpgto_names" value=$condpgto_names|@array_merge:array($cond.DESCRICAO)}
                                            {/foreach}
                                        {/if}
                                        <select class="form-control" id="condpgto" name="condpgto">
                                            <option value="">Selecione...</option>
                                            {html_options values=$condpgto_ids output=$condpgto_names selected=$dados.CONDPGTO}
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="genero">Gênero Saída <i class="fa fa-info-circle param-tip" data-toggle="tooltip" title="Somente gêneros de recebimento (+). Usado em pedidos e NF de saída."></i></label>
                                            {assign var="generos_saida_ids" value=array()}
                                            {assign var="generos_saida_names" value=array()}
                                            {if $generos_saida}
                                                {foreach from=$generos_saida item=gen}
                                                    {assign var="generos_saida_ids" value=$generos_saida_ids|@array_merge:array($gen.ID)}
                                                    {assign var="generos_saida_names" value=$generos_saida_names|@array_merge:array($gen.ID|cat:" - "|cat:$gen.DESCRICAO|cat:" (+)")}
                                                {/foreach}
                                            {/if}
                                            <select class="form-control" id="genero" name="genero">
                                                <option value="">Selecione...</option>
                                                {html_options values=$generos_saida_ids output=$generos_saida_names selected=$dados.GENERO}
                                            </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="genero_extrato">Gênero Entrada <i class="fa fa-info-circle param-tip" data-toggle="tooltip" title="Somente gêneros de pagamento (-). Usado em NF de entrada, XML e extrato."></i></label>
                                            {assign var="generos_entrada_ids" value=array()}
                                            {assign var="generos_entrada_names" value=array()}
                                            {if $generos_entrada}
                                                {foreach from=$generos_entrada item=gen}
                                                    {assign var="generos_entrada_ids" value=$generos_entrada_ids|@array_merge:array($gen.ID)}
                                                    {assign var="generos_entrada_names" value=$generos_entrada_names|@array_merge:array($gen.ID|cat:" - "|cat:$gen.DESCRICAO|cat:" (-)")}
                                                {/foreach}
                                            {/if}
                                        <select class="form-control" id="genero_extrato" name="genero_extrato">
                                            <option value="">Selecione...</option>
                                            {html_options values=$generos_entrada_ids output=$generos_entrada_names selected=$dados.GENERO_EXTRATO}
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">

                                 <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="conta">Conta <i class="fa fa-info-circle param-tip" data-toggle="tooltip" title="Conta financeira padrão dos lançamentos."></i></label>
                                        {assign var="contas_ids" value=array()}
                                        {assign var="contas_names" value=array()}
                                        {if $contas}
                                            {foreach from=$contas item=conta}
                                                {assign var="contas_ids" value=$contas_ids|@array_merge:array($conta.ID)}
                                                {assign var="contas_names" value=$contas_names|@array_merge:array($conta.DESCRICAO)}
                                            {/foreach}
                                        {/if}
                                        <select class="form-control" id="conta" name="conta">
                                            <option value="">Selecione...</option>
                                            {html_options values=$contas_ids output=$contas_names selected=$dados.CONTA}
                                        </select>
                                    </div>
                                </div>
                            
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="centrocusto">Centro de Custo <i class="fa fa-info-circle param-tip" data-toggle="tooltip" title="Centro de custo padrão das movimentações financeiras. Se vazio, será igual ao da empresa selecionada."></i></label>
                                        {assign var="centros_ids" value=array()}
                                        {assign var="centros_names" value=array()}
                                        {if $centros_custo}
                                            {foreach from=$centros_custo item=cc}
                                                {assign var="centros_ids" value=$centros_ids|@array_merge:array($cc.ID)}
                                                {assign var="centros_names" value=$centros_names|@array_merge:array($cc.DESCRICAO)}
                                            {/foreach}
                                        {/if}
                                        <select class="form-control" id="centrocusto" name="centrocusto">
                                            <option value="">Selecione...</option>
                                            {html_options values=$centros_ids output=$centros_names selected=$dados.CENTROCUSTO}
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="percdescmaximo">% Desconto Máximo <i class="fa fa-info-circle param-tip" data-toggle="tooltip" title="Limite de desconto permitido na nota."></i></label>
                                        <input type="text" class="form-control money" maxlength="4" id="percdescmaximo" name="percdescmaximo" value="{$dados.PERCDESCMAXIMO}">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="percalculo">% Cálculo <i class="fa fa-info-circle param-tip" data-toggle="tooltip" title="Percentual usado em cálculos de preço e custo."></i></label>
                                        <input type="text" class="form-control money" maxlength="4" id="percalculo" name="percalculo" value="{$dados.PERCALCULO}">
                                    </div>
                                </div>
                            </div>
                            
                            {* Seção 4: Configurações de Estoque *}
                            <div class="row">
                                <div class="col-md-12">
                                    <h4><i class="fa fa-cubes"></i> Configurações de Estoque</h4>
                                    <hr>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="clientepadrao">Cliente Padrão <i class="fa fa-info-circle param-tip" data-toggle="tooltip" title="Cliente usado quando nenhum outro for informado."></i></label>
                                        {assign var="clientes_ids" value=array()}
                                        {assign var="clientes_names" value=array()}
                                        {if $clientes}
                                            {foreach from=$clientes item=cliente}
                                                {assign var="clientes_ids" value=$clientes_ids|@array_merge:array($cliente.ID)}
                                                {assign var="clientes_names" value=$clientes_names|@array_merge:array($cliente.DESCRICAO)}
                                            {/foreach}
                                        {/if}
                                        <select class="form-control" id="clientepadrao" name="clientepadrao">
                                            <option value="">Selecione...</option>
                                            {html_options values=$clientes_ids output=$clientes_names selected=$dados.CLIENTEPADRAO}
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="grupopadrao">Grupo Padrão <i class="fa fa-info-circle param-tip" data-toggle="tooltip" title="Grupo de produto sugerido no cadastro."></i></label>
                                        {assign var="grupos_ids" value=array()}
                                        {assign var="grupos_names" value=array()}
                                        {if $grupos}
                                            {foreach from=$grupos item=grupo}
                                                {assign var="grupos_ids" value=$grupos_ids|@array_merge:array($grupo.ID)}
                                                {assign var="grupos_names" value=$grupos_names|@array_merge:array($grupo.DESCRICAO)}
                                            {/foreach}
                                        {/if}
                                        <select class="form-control" id="grupopadrao" name="grupopadrao">
                                            <option value="">Selecione...</option>
                                            {html_options values=$grupos_ids output=$grupos_names selected=$dados.GRUPOPADRAO}
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="precobase">Preço Base <i class="fa fa-info-circle param-tip" data-toggle="tooltip" title="Usa preço de custo ou de venda como referência."></i></label>
                                        <select class="form-control" id="precobase" name="precobase">
                                            <option value="C" {if $dados.PRECOBASE == 'C' || !$dados.PRECOBASE}selected{/if}>C - Custo</option>
                                            <option value="V" {if $dados.PRECOBASE == 'V'}selected{/if}>V - Venda</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            </br>
                            
                            <div class="param-box">
                                <h4 class="section-title"><i class="fa fa-cogs"></i> Configura&ccedil;&otilde;es de Controle</h4>
                                <div class="param-item">
                                    <label class="param-label"><i class="fa fa-search"></i> Consulta Estoque Zero <i class="fa fa-info-circle param-tip" data-toggle="tooltip" title="Exibe produtos sem saldo nas consultas de estoque."></i></label>
                                    <div class="radio-group">
                                        {html_radios class="flat" name="consultaestoquezero" values=$boolean_ids output=$boolean_names selected=$dados.CONSULTAESTOQUEZERO|default:'S' separator="&emsp;"}
                                    </div>
                                </div>
                                <div class="param-item">
                                    <label class="param-label"><i class="fa fa-cubes"></i> Controla Estoque <i class="fa fa-info-circle param-tip" data-toggle="tooltip" title="Atualiza o saldo ao movimentar produtos."></i></label>
                                    <div class="radio-group">
                                        {html_radios class="flat" name="controlaestoque" values=$boolean_ids output=$boolean_names selected=$dados.CONTROLAESTOQUE|default:'S' separator="&emsp;"}
                                    </div>
                                </div>
                                <div class="param-item">
                                    <label class="param-label"><i class="fa fa-money"></i> Integra Financeiro <i class="fa fa-info-circle param-tip" data-toggle="tooltip" title="Gera lançamento financeiro ao emitir a nota."></i></label>
                                    <div class="radio-group">
                                        {html_radios class="flat" name="integrafin" values=$boolean_ids output=$boolean_names selected=$dados.INTEGRAFIN|default:'S' separator="&emsp;"}
                                    </div>
                                </div>
                                <div class="param-item">
                                    <label class="param-label"><i class="fa fa-check-square-o"></i> Valida NF Auto <i class="fa fa-info-circle param-tip" data-toggle="tooltip" title="Valida a nota automaticamente ao salvar ou emitir."></i></label>
                                    <div class="radio-group">
                                        {html_radios class="flat" name="validanfauto" values=$boolean_ids output=$boolean_names selected=$dados.VALIDANFAUTO|default:'S' separator="&emsp;"}
                                    </div>
                                </div>
                                <div class="row" style="margin-top: 10px;">
                                    <div class="col-md-6">
                                        <div class="tributo-item">
                                            <label for="tipovalidacao">Tipo de Valida&ccedil;&atilde;o <i class="fa fa-info-circle param-tip" data-toggle="tooltip" title="Nível de rigor na validação: normal, rigorosa ou simples."></i></label>
                                            <select class="form-control input-sm" id="tipovalidacao" name="tipovalidacao">
                                                <option value="N" {if $dados.TIPOVALIDACAO == 'N' || !$dados.TIPOVALIDACAO}selected{/if}>N - Normal</option>
                                                <option value="R" {if $dados.TIPOVALIDACAO == 'R'}selected{/if}>R - Rigorosa</option>
                                                <option value="S" {if $dados.TIPOVALIDACAO == 'S'}selected{/if}>S - Simples</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="param-box">
                                <h4 class="section-title"><i class="fa fa-file-code-o"></i> Importa&ccedil;&atilde;o XML</h4>
                                <div class="param-item">
                                    <label class="param-label"><i class="fa fa-cubes"></i> Conferir estoque na importa&ccedil;&atilde;o <i class="fa fa-info-circle param-tip" data-toggle="tooltip" title="Confere saldo ao importar XML de entrada."></i></label>
                                    <div class="radio-group">
                                        {html_radios class="flat" name="xmlconferirestoque" values=$boolean_ids output=$boolean_names selected=$dados.XMLCONFERIRESTOQUE|default:'N' separator="&emsp;"}
                                    </div>
                                </div>
                                <div class="param-item">
                                    <label class="param-label"><i class="fa fa-tag"></i> Manter origem CST do XML <i class="fa fa-info-circle param-tip" data-toggle="tooltip" title="Mantém CST e origem informados no XML importado."></i></label>
                                    <div class="radio-group">
                                        {html_radios class="flat" name="xmlmanterorigemcst" values=$boolean_ids output=$boolean_names selected=$dados.XMLMANTERORIGEMCST|default:'S' separator="&emsp;"}
                                    </div>
                                </div>
                            </div>

                            <div class="param-box">
                                <h4 class="section-title"><i class="fa fa-calculator"></i> Custo de Reposi&ccedil;&atilde;o</h4>
                                <div class="param-item">
                                    <label class="param-label"><i class="fa fa-percent"></i> Calcular IPI no custo de reposi&ccedil;&atilde;o <i class="fa fa-info-circle param-tip" data-toggle="tooltip" title="Inclui IPI no cálculo do custo de reposição."></i></label>
                                    <div class="radio-group">
                                        {html_radios class="flat" name="calcula_ipi_custo_reposicao" values=$boolean_ids output=$boolean_names selected=$dados.CALCULA_IPI_CUSTO_REPOSICAO|default:'N' separator="&emsp;"}
                                    </div>
                                </div>
                                <div class="param-item">
                                    <label class="param-label"><i class="fa fa-percent"></i> Calcular ST no custo de reposi&ccedil;&atilde;o <i class="fa fa-info-circle param-tip" data-toggle="tooltip" title="Inclui substituição tributária no custo de reposição."></i></label>
                                    <div class="radio-group">
                                        {html_radios class="flat" name="calcula_st_custo_reposicao" values=$boolean_ids output=$boolean_names selected=$dados.CALCULA_ST_CUSTO_REPOSICAO|default:'N' separator="&emsp;"}
                                    </div>
                                </div>
                            </div>

                            
                            {* Seção 6: Configurações NFS-e *}
                            <div class="row">
                                <div class="col-md-12">
                                    <h4><b><i class="fa fa-file-o"></i> Configurações NFS-e</b></h4>
                                </div>
                            </div>

                            </br>
                            
                            <div class="row">

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="nfs_serie">Série NFS-e <i class="fa fa-info-circle param-tip" data-toggle="tooltip" title="Série usada na emissão de nota de serviço."></i></label>
                                        <input type="number" class="form-control" id="nfs_serie" name="nfs_serie" 
                                               value="{$dados.NFS_SERIE}">
                                    </div>
                                </div>

                                <!-- INSS -->
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inss">INSS (%) <i class="fa fa-info-circle param-tip" data-toggle="tooltip" title="Percentual de retenção de INSS na NFS-e."></i></label>
                                        <input type="text" class="form-control money" maxlength="5" id="nfs_inss" name="nfs_inss" value="{$dados.NFS_INSS}">
                                    </div>
                                </div>

                                <!-- PIS -->
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="pis">PIS (%) <i class="fa fa-info-circle param-tip" data-toggle="tooltip" title="Percentual de retenção de PIS na NFS-e."></i></label>
                                        <input type="text" class="form-control money" maxlength="5" id="nfs_pis" name="nfs_pis" value="{$dados.NFS_PIS}">
                                    </div>
                                </div>

                                <!-- COFINS -->
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="cofins">COFINS (%) <i class="fa fa-info-circle param-tip" data-toggle="tooltip" title="Percentual de retenção de COFINS na NFS-e."></i></label>
                                        <input type="text" class="form-control money" maxlength="5" id="nfs_cofins" name="nfs_cofins" value="{$dados.NFS_COFINS}">
                                    </div>
                                </div>

                                <!-- IR -->
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="ir">IR (%) <i class="fa fa-info-circle param-tip" data-toggle="tooltip" title="Percentual de retenção de IR na NFS-e."></i></label>
                                        <input type="text" class="form-control money" maxlength="5" id="nfs_ir" name="nfs_ir" value="{$dados.NFS_IR}">
                                    </div>
                                </div>

                                <!-- Contribuição Social -->
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="contribuicao_social">Contribuição Social (%) <i class="fa fa-info-circle param-tip" data-toggle="tooltip" title="Percentual de contribuição social retida na NFS-e."></i></label>
                                        <input type="text" class="form-control money" maxlength="5" id="nfs_contribuicao_social" name="nfs_contribuicao_social" value="{$dados.NFS_CONTRIBUICAO_SOCIAL}">
                                    </div>
                                </div>

                            </div>
                            
                            <div class="row">

                                <!-- Serviço -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="servico">Serviço <i class="fa fa-info-circle param-tip" data-toggle="tooltip" title="Código do serviço padrão na NFS-e."></i></label>
                                        <select class="form-control" id="nfs_servico" name="nfs_servico">
                                            <option value="">Selecione um serviço...</option>
                                            {html_options values=$servicos_ids output=$servicos_names selected=$servico_id}
                                        </select>
                                    </div>
                                </div>

                                <!-- Situação Tributária -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="situacao_tributaria">Situação Tributária <i class="fa fa-info-circle param-tip" data-toggle="tooltip" title="Situação tributária padrão do serviço."></i></label>
                                        <select class="form-control" id="nfs_situacao_tributaria" name="nfs_situacao_tributaria">
                                            <option value="">Selecione...</option>
                                            {html_options values=$situacao_tributaria_ids output=$situacao_tributaria_names selected=$situacao_tributaria_id}
                                        </select>
                                    </div>
                                </div>

                                <!-- Parcela -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="parcela">Parcela <i class="fa fa-info-circle param-tip" data-toggle="tooltip" title="Forma de parcelamento padrão da NFS-e."></i></label>
                                        <select class="form-control" id="nfs_parcela" name="nfs_parcela">
                                            <option value="">Selecione...</option>
                                            {html_options values=$parcelas_ids output=$parcelas_names selected=$parcela_id}
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nfs_user">Usuário <i class="fa fa-info-circle param-tip" data-toggle="tooltip" title="Login de acesso ao portal da NFS-e."></i></label>
                                        <input type="text" class="form-control" id="nfs_user" name="nfs_user" maxlength="50" value="{$dados.NFS_USER}">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nfs_password">Senha <i class="fa fa-info-circle param-tip" data-toggle="tooltip" title="Senha de acesso ao portal da NFS-e."></i></label>
                                        <input type="password" class="form-control" id="nfs_password" name="nfs_password" maxlength="50" value="{$dados.NFS_PASSWORD}" autocomplete="new-password">
                                    </div>
                                </div>
                            </div>

                            </br>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


{include file="template/database.inc"}

<script src="{$bootstrap}/js/input_mask/jquery.maskMoney.js"></script>
<script>
    $(document).ready(function() {
        $('[data-toggle="tooltip"]').tooltip({ container: 'body' });

        $('#filial').on('change', function () {
            sincronizarCentroCustoEmpresa();
        });

        $(".money").maskMoney({
            decimal: ".",
            thousands: "",
            allowNegative: true,
            precision: 2
        });

        $(".money").blur(function() {
            var value = $(this).val();
            if (value === "") {
                $(this).val("0.00");
            }
        });

        {if $swalText}
        Swal.fire({
            icon: '{$swalIcon}',
            title: '{$swalTitle|escape:'javascript'}',
            text: '{$swalText|escape:'javascript'}',
            width: 510,
            {if $swalAutoClose}
            timer: 3000,
            showConfirmButton: false
            {else}
            confirmButtonText: 'OK'
            {/if}
        });
        {/if}
    });
</script>

