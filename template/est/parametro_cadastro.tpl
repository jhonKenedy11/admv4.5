<style>
.right_col { padding-left: 5px !important; padding-right: 5px !important; }

.x_panel { padding-top: 5px !important; }

.form-control, .x_panel { border-radius: 5px;}

/* Remove setas no Chrome, Edge, Safari e Opera */
input[type=number]::-webkit-inner-spin-button,
input[type=number]::-webkit-outer-spin-button {
  -webkit-appearance: none;
  margin: 0;
}
</style>

{* Template para cadastro/alteração dos parâmetros de estoque *}
{* Arquivo: template/est/parametro_cadastro.tpl *}

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
                                    <button type="submit" form="formParametros" class="btn btn-success btn-sm" onclick="submitSavedChanges('{$dados.ID}')">
                                        <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span>
                                        Salvar Alterações
                                    </button>
                                </li>
                                <li>
                                    <button type="button" class="btn btn-default btn-sm" onclick="voltarListagem()">
                                        <span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span>
                                        Voltar
                                    </button>
                                </li>
                            {else}
                                {* Botões para modo cadastro *}
                                <li>
                                    <button type="submit" form="formParametros" class="btn btn-primary btn-sm">
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
                                    <button type="button" class="btn btn-default btn-sm" onclick="voltarListagem()">
                                        <span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span>
                                        Voltar
                                    </button>
                                </li>
                            {/if}
                        </ul>
                        
                        <div class="clearfix"></div>
                    </div>
                    
                    <div class="x_content">
                        <form class="full" id="parametro" name="parametro" method="post" ACTION={$SCRIPT_NAME}>
                            <input name=mod type=hidden value="est">
                            <input name=form type=hidden value="parametro">
                            <input name=submenu type=hidden value="{$submenu}">
                            <input name=id type=hidden value="{$id}">
                            
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
                                        <label for="filial">Filial/Empresa <span class="text-danger">*</span></label>
                                        <select class="form-control" id="filial" name="filial" required 
                                                {if $submenu == 'alterar'} disabled {/if}>
                                            <option value="">Selecione uma filial...</option>
                                            {html_options values=$empresas_ids output=$empresas_names selected=$empresa_id}
                                        </select>
                                    </div>
                                </div>  

                                <!-- Modelo -->
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="modelo">Modelo <span class="text-danger">*</span></label>
                                        <select class="form-control" id="modelo" name="modelo" required
                                                {if $submenu == 'alterar'} disabled {/if}>
                                            <option value="55" {if $dados.MODELO == '55' || !$dados.MODELO}selected{/if}>55 - NFe</option>
                                            <option value="65" {if $dados.MODELO == '65'}selected{/if}>65 - NFCe</option>
                                            <option value="57" {if $dados.MODELO == '57'}selected{/if}>57 - CTe</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Série -->
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="serie">Série</label>
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
                                        <label for="cfop">CFOP</label>
                                        <input type="text" class="form-control" id="cfop" name="cfop" 
                                               value="{$dados.CFOP}" maxlength="15">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="natoperacao">Natureza da Operação</label>
                                        <input type="text" class="form-control" id="natoperacao" name="natoperacao" 
                                               value="{$dados.NATOPERACAO}" maxlength="20">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="natopentrada">Natureza Op. Entrada</label>
                                        <input type="number" class="form-control" id="natopentrada" name="natopentrada" 
                                               value="{$dados.NATOPENTRADA}">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="generomovimento">Gênero Movimento</label>
                                        <input type="text" class="form-control" id="generomovimento" name="generomovimento" 
                                               value="{$dados.GENEROMOVIMENTO}" maxlength="4">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="modofin">Modo Financeiro</label>
                                        <select class="form-control" id="modofin" name="modofin">
                                            <option value="">Selecione...</option>
                                            <option value="A" {if $dados.MODOFIN == 'A'}selected{/if}>A - À Vista</option>
                                            <option value="P" {if $dados.MODOFIN == 'P'}selected{/if}>P - À Prazo</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="tipodoc">Tipo Documento</label>
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
                                        <label for="condpgto">Condição de Pagamento</label>
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
                                        <label for="genero">Gênero</label>
                                        {assign var="generos_ids" value=array()}
                                        {assign var="generos_names" value=array()}
                                        {if $generos}
                                            {foreach from=$generos item=gen}
                                                {assign var="generos_ids" value=$generos_ids|@array_merge:array($gen.ID)}
                                                {assign var="generos_names" value=$generos_names|@array_merge:array($gen.DESCRICAO)}
                                            {/foreach}
                                        {/if}
                                        <select class="form-control" id="genero" name="genero">
                                            <option value="">Selecione...</option>
                                            {html_options values=$generos_ids output=$generos_names selected=$dados.GENERO}
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="conta">Conta</label>
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
                            </div>
                            
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="centrocusto">Centro de Custo</label>
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
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="percdescmaximo">% Desconto Máximo</label>
                                        <input type="text" class="form-control money" maxlength="4" id="percdescmaximo" name="percdescmaximo" value="{$dados.PERCDESCMAXIMO}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="percalculo">% Cálculo</label>
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
                                        <label for="clientepadrao">Cliente Padrão</label>
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
                                        <label for="grupopadrao">Grupo Padrão</label>
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
                                        <label for="precobase">Preço Base</label>
                                        <select class="form-control" id="precobase" name="precobase">
                                            <option value="C" {if $dados.PRECOBASE == 'C' || !$dados.PRECOBASE}selected{/if}>C - Custo</option>
                                            <option value="V" {if $dados.PRECOBASE == 'V'}selected{/if}>V - Venda</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            </br>
                            
                            {* Seção 5: Configurações de Controle *}
                            <div class="row">
                                <div class="col-md-12">
                                    <h4><b><i class="fa fa-cogs"></i> Configurações de Controle</b></h4>
                                </div>
                            </div>

                            </br>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="consultaestoquezero">Consulta Estoque Zero</label>
                                        <select class="form-control" id="consultaestoquezero" name="consultaestoquezero">
                                            <option value="S" {if $dados.CONSULTAESTOQUEZERO == 'S' || !$dados.CONSULTAESTOQUEZERO}selected{/if}>Sim</option>
                                            <option value="N" {if $dados.CONSULTAESTOQUEZERO == 'N'}selected{/if}>Não</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="controlaestoque">Controla Estoque</label>
                                        <select class="form-control" id="controlaestoque" name="controlaestoque">
                                            <option value="S" {if $dados.CONTROLAESTOQUE == 'S' || !$dados.CONTROLAESTOQUE}selected{/if}>Sim</option>
                                            <option value="N" {if $dados.CONTROLAESTOQUE == 'N'}selected{/if}>Não</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="integrafin">Integra Financeiro</label>
                                        <select class="form-control" id="integrafin" name="integrafin">
                                            <option value="S" {if $dados.INTEGRAFIN == 'S' || !$dados.INTEGRAFIN}selected{/if}>Sim</option>
                                            <option value="N" {if $dados.INTEGRAFIN == 'N'}selected{/if}>Não</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="validanfauto">Valida NF Auto</label>
                                        <select class="form-control" id="validanfauto" name="validanfauto">
                                            <option value="S" {if $dados.VALIDANFAUTO == 'S' || !$dados.VALIDANFAUTO}selected{/if}>Sim</option>
                                            <option value="N" {if $dados.VALIDANFAUTO == 'N'}selected{/if}>Não</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="tipovalidacao">Tipo de Validação</label>
                                        <select class="form-control" id="tipovalidacao" name="tipovalidacao">
                                            <option value="N" {if $dados.TIPOVALIDACAO == 'N' || !$dados.TIPOVALIDACAO}selected{/if}>N - Normal</option>
                                            <option value="R" {if $dados.TIPOVALIDACAO == 'R'}selected{/if}>R - Rigorosa</option>
                                            <option value="S" {if $dados.TIPOVALIDACAO == 'S'}selected{/if}>S - Simples</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            </br>
                            
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
                                        <label for="nfs_serie">Série NFS-e</label>
                                        <input type="number" class="form-control" id="nfs_serie" name="nfs_serie" 
                                               value="{$dados.NFS_SERIE}">
                                    </div>
                                </div>

                                <!-- INSS -->
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inss">INSS (%)</label>
                                        <input type="text" class="form-control money" maxlength="5" id="inss" name="inss" value="{$dados.INSS}">
                                    </div>
                                </div>

                                <!-- PIS -->
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="pis">PIS (%)</label>
                                        <input type="text" class="form-control money" maxlength="5" id="pis" name="pis" value="{$dados.PIS}">
                                    </div>
                                </div>

                                <!-- COFINS -->
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="cofins">COFINS (%)</label>
                                        <input type="text" class="form-control money" maxlength="5" id="cofins" name="cofins" value="{$dados.COFINS}">
                                    </div>
                                </div>

                                <!-- IR -->
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="ir">IR (%)</label>
                                        <input type="text" class="form-control money" maxlength="5" id="ir" name="ir" value="{$dados.IR}">
                                    </div>
                                </div>

                                <!-- Contribuição Social -->
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="contribuicao_social">Contribuição Social (%)</label>
                                        <input type="text" class="form-control money" maxlength="5" id="contribuicao_social" name="contribuicao_social" value="{$dados.CONTRIBUICAO_SOCIAL}">
                                    </div>
                                </div>

                            </div>
                            
                            <div class="row">

                                <!-- Serviço -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="servico">Serviço</label>
                                        <select class="form-control" id="servico" name="servico">
                                            <option value="">Selecione um serviço...</option>
                                            {html_options values=$servicos_ids output=$servicos_names selected=$servico_id}
                                        </select>
                                    </div>
                                </div>

                                <!-- Situação Tributária -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="situacao_tributaria">Situação Tributária</label>
                                        <select class="form-control" id="situacao_tributaria" name="situacao_tributaria">
                                            <option value="">Selecione...</option>
                                            {html_options values=$situacao_tributaria_ids output=$situacao_tributaria_names selected=$situacao_tributaria_id}
                                        </select>
                                    </div>
                                </div>

                                <!-- Parcela -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="parcela">Parcela</label>
                                        <select class="form-control" id="parcela" name="parcela">
                                            <option value="">Selecione...</option>
                                            {html_options values=$parcelas_ids output=$parcelas_names selected=$parcela_id}
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


{include file="template/database.inc"}

<script src="https://cdn.rawgit.com/plentz/jquery-maskmoney/master/dist/jquery.maskMoney.min.js"></script>
<script>
    $(document).ready(function() {
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
    });
</script>

