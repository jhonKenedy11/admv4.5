<link href="{$bootstrap}/jQuery-Smart-Wizard/styles/smart_wizard.css" rel="stylesheet">

<style>
    .stepContainer { height: auto !important; min-height: 400px; overflow: visible !important; }
    .stepContainer .content { height: auto !important; overflow: visible !important; }
    #tabela-itens-devolucao { margin-bottom: 20px; }
    .totais-devolucao-box { background: #f8f9fa; border: 1px solid #dee2e6; border-radius: 5px; padding: 12px; margin-bottom: 15px; }
    .totais-devolucao-box .valor { font-weight: bold; text-align: right; }
    #painel-tributos-itens .painel-tributo-item { margin-bottom: 15px; border: 1px solid #ddd; border-radius: 4px; }
    #painel-tributos-itens .painel-tributo-item .panel-heading { padding: 8px 12px; background: #f5f5f5; }
    #painel-tributos-itens .trib-item-heading { cursor: pointer; user-select: none; }
    #painel-tributos-itens .trib-item-heading .trib-chevron { margin-right: 6px; width: 12px; }
    #painel-tributos-itens .trib-item-heading:hover { background: #ebebeb; }
    #painel-tributos-itens .trib-item-body { border-top: 1px solid #eee; }
    #painel-tributos-itens .trib-campo { margin-bottom: 8px; }
    #painel-tributos-itens .trib-campo label { font-size: 11px; margin-bottom: 2px; display: block; color: #555; }
    #painel-tributos-itens .trib-grupo-panel { margin: 8px 0; border: 1px solid #e8e8e8; border-radius: 3px; }
    #painel-tributos-itens .trib-grupo-heading { cursor: pointer; user-select: none; padding: 6px 10px; background: #fafafa; font-size: 12px; font-weight: bold; color: #337ab7; }
    #painel-tributos-itens .trib-grupo-heading .trib-grupo-chevron { margin-right: 6px; width: 12px; }
    #painel-tributos-itens .trib-grupo-heading:hover { background: #f0f0f0; }
    #painel-tributos-itens .trib-grupo-body { padding: 8px 10px 4px; border-top: 1px solid #eee; }
    #wizard-devolucao .stepContainer,
    #wizard-devolucao .actionBar { position: relative; z-index: 1; }
    #wizard-devolucao .sw-btn-group .buttonNext,
    #wizard-devolucao .sw-btn-group .buttonPrevious,
    #wizard-devolucao .sw-btn-group .buttonFinish {
        padding: 4px 12px !important;
        font-size: 12px !important;
        line-height: 1.4 !important;
    }
    #wizard-devolucao .btn-wizard-acao { padding: 4px 10px; font-size: 12px; }
    .form-control{ border-radius: 5px !important; }
</style>

<div class="right_col" role="main">
    <div class="col-md-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>Cadastro de Nota Fiscal <small>{if $manual}Cadastro manual{else}Assistente{/if}</small></h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li>
                        <button type="button" class="btn btn-default btn-sm" onclick="cancelarWizardDevolucao();">
                            <i class="fa fa-times"></i> Cancelar
                        </button>
                    </li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <input type="hidden" id="idNfOrigem" value="{$idNfOrigem}">
                <input type="hidden" id="idNfDev" value="{$idNfDev}">
                <input type="hidden" id="origemTela" value="{$origem}">
                <input type="hidden" id="submenuTela" value="{$submenuTela}">
                <input type="hidden" id="manual" value="{$manual}">
                <input type="hidden" id="ctx_id_pessoa" value="">

                <div id="wizard-devolucao" class="form_wizard wizard_horizontal">
                    <ul class="wizard_steps">
                        <li><a href="#step_ctx"><span class="step_no">1</span><small>Cabeçalho</small></a></li>
                        <li><a href="#step_prod"><span class="step_no">2</span><small>Produtos</small></a></li>
                        <li><a href="#step_trib"><span class="step_no">3</span><small>Tributação</small></a></li>
                        <li><a href="#step_val"><span class="step_no">4</span><small>Validação</small></a></li>
                        <li><a href="#step_fim"><span class="step_no">5</span><small>Salvar</small></a></li>
                    </ul>

                    <div id="step_ctx">
                        <div class="panel panel-primary">
                            <div class="panel-heading"><h3 class="panel-title">Cabeçalho da Nota Fiscal</h3></div>
                            <div class="panel-body">
                                <div class="form-horizontal form-label-left">
                                    <div class="form-group" id="grp_cenario_manual" style="display:none;">
                                        <label class="col-md-2 control-label">Cenário</label>
                                        <div class="col-md-4">
                                            <select class="form-control input-sm" id="ctx_cenario_codigo">
                                                <option value="DEVOLUCAO_VENDA">Devolução de venda</option>
                                                <option value="DEVOLUCAO_COMPRA">Devolução de compra</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Pessoa</label>
                                        <div class="col-md-9">
                                            <div class="input-group" id="grp_pessoa_manual" style="display:none;">
                                                <input type="text" class="form-control input-sm" id="ctx_pessoa_nome" readonly placeholder="Cliente ou fornecedor">
                                                <span class="input-group-btn">
                                                    <button type="button" class="btn btn-primary btn-sm" onclick="abrirPessoaDevolucao();">
                                                        <span class="glyphicon glyphicon-search"></span>
                                                    </button>
                                                </span>
                                            </div>
                                            <input type="text" class="form-control input-sm" id="ctx_pessoa" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">NF Origem</label>
                                        <div class="col-md-1">
                                            <input type="text" class="form-control input-sm" id="ctx_nf_numero" placeholder="Número" readonly>
                                        </div>
                                        <div class="col-md-1">
                                            <input type="text" class="form-control input-sm" id="ctx_nf_serie" placeholder="Série" readonly>
                                        </div>
                                        <div class="col-md-8">
                                            <label class="col-md-4 control-label">NFe Referenciada</label>
                                            <div class="col-md-7"><input type="text" class="form-control input-sm" id="ctx_chnfe" maxlength="44" placeholder="44 dígitos da chave de acesso da NF de origem"></div>
                                        </div>
                                        <input type="hidden" id="ctx_nf_origem">
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Nat. Operação</label>
                                        <div class="col-md-8">
                                            <select class="form-control input-sm" id="ctx_id_natop"></select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Finalidade</label>
                                        <div class="col-md-8">
                                            <select class="form-control input-sm" id="ctx_finalidade_emissao">
                                                {html_options values=$finalidadeEmissao_ids output=$finalidadeEmissao_names selected=$finalidadeEmissao_id}
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group" id="grp_tp_nf_credito" style="display:none;">
                                        <label class="col-md-2 control-label">Tipo Crédito</label>
                                        <div class="col-md-8">
                                            <select class="form-control input-sm" id="ctx_tp_nf_credito">
                                                <option value="">-- Selecione --</option>
                                            </select>
                                            <div id="info_tp_nf_credito" class="small text-muted" style="margin-top:4px;line-height:1.4;"></div>
                                        </div>
                                    </div>

                                    <div class="form-group" id="grp_tp_nf_debito" style="display:none;">
                                        <label class="col-md-2 control-label">Tipo Débito</label>
                                        <div class="col-md-8">
                                            <select class="form-control input-sm" id="ctx_tp_nf_debito">
                                                <option value="">-- Selecione --</option>
                                            </select>
                                            <div id="info_tp_nf_debito" class="small text-muted" style="margin-top:4px;line-height:1.4;"></div>
                                        </div>
                                    </div>

                                    <div class="form-group" style="display:none;">
                                        <label class="col-md-2 control-label">Data emissão</label>
                                        <div class="col-md-3"><input type="text" class="form-control input-sm" id="ctx_emissao" readonly></div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Modalidade frete</label>
                                        <div class="col-md-8">
                                            <select class="form-control input-sm" id="ctx_mod_frete">
                                                {html_options values=$modFrete_ids output=$modFrete_names selected=$modFrete_id}
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Transportador</label>
                                        <div class="col-md-8">
                                            <input type="hidden" id="ctx_transportador" value="">
                                            <div class="input-group">
                                                <input type="text" class="form-control input-sm" id="ctx_transportador_nome" readonly placeholder="Transportador que realiza o frete">
                                                <span class="input-group-btn">
                                                    <button type="button" class="btn btn-primary btn-sm" onclick="abrirTransportadorDevolucao();">
                                                        <span class="glyphicon glyphicon-search"></span>
                                                    </button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Veículo</label>
                                        <div class="col-md-2">
                                            <input type="text" class="form-control input-sm" id="ctx_placa_veiculo" placeholder="Placa" maxlength="8">
                                        </div>
                                        <div class="col-md-2">
                                            <input type="text" class="form-control input-sm" id="ctx_cod_antt" placeholder="Cód. ANTT">
                                        </div>
                                        <div class="col-md-1">
                                            <input type="text" class="form-control input-sm" id="ctx_uf_veiculo" placeholder="UF" maxlength="2" style="text-transform:uppercase;">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Volumes</label>
                                        <div class="col-md-2">
                                            <input type="text" class="form-control input-sm" id="ctx_vol_especie" placeholder="Espécie">
                                        </div>
                                        <div class="col-md-2">
                                            <input type="text" class="form-control input-sm" id="ctx_vol_marca" placeholder="Marca">
                                        </div>
                                        <div class="col-md-1">
                                            <input type="text" class="form-control input-sm" id="ctx_volume" placeholder="Qtd">
                                        </div>
                                        <div class="col-md-2">
                                            <input type="text" class="form-control input-sm" id="ctx_vol_peso_liq" placeholder="Peso líq.">
                                        </div>
                                        <div class="col-md-2">
                                            <input type="text" class="form-control input-sm" id="ctx_vol_peso_bruto" placeholder="Peso bruto">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Observação</label>
                                        <div class="col-md-8">
                                            <textarea class="form-control input-sm" id="ctx_obs" rows="2" placeholder="Observações da NF de devolução"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Frete</label>
                                        <div class="col-md-2">
                                            <div class="input-group input-group-sm">
                                                <span class="input-group-addon">R$</span>
                                                <input type="text" class="form-control text-right" id="ctx_frete" value="0,00">
                                            </div>
                                        </div>
                                        <label class="col-md-1 control-label">Seguro</label>
                                        <div class="col-md-2">
                                            <div class="input-group input-group-sm">
                                                <span class="input-group-addon">R$</span>
                                                <input type="text" class="form-control text-right" id="ctx_seguro" value="0,00">
                                            </div>
                                        </div>
                                        <label class="col-md-2 control-label">Desp. acessórias</label>
                                        <div class="col-md-2">
                                            <div class="input-group input-group-sm">
                                                <span class="input-group-addon">R$</span>
                                                <input type="text" class="form-control text-right" id="ctx_desp_acessorias" value="0,00">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="step_prod">
                        <div class="panel panel-primary">
                            <div class="panel-heading"><h3 class="panel-title">Seleção de produtos</h3></div>
                            <div class="panel-body">
                                <div id="painel-add-produto-manual" class="well well-sm" style="display:none; margin-bottom:15px;">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label>Código produto</label>
                                            <input type="text" class="form-control input-sm" id="inp_cod_produto" placeholder="Código">
                                        </div>
                                        <div class="col-md-2" style="padding-top:24px;">
                                            <button type="button" class="btn btn-primary btn-sm btn-block" id="btn_add_produto_manual">
                                                <i class="fa fa-plus"></i> Incluir
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                <table id="tabela-itens-devolucao" class="table table-bordered table-striped jambo_table">
                                    <thead>
                                        <tr>
                                            <th width="4%"><input type="checkbox" id="chk_todos_itens"></th>
                                            <th>Produto</th>
                                            <th width="8%">Qtde orig.</th>
                                            <th width="10%">Qtde dev.</th>
                                            <th width="10%">Vl. unit.</th>
                                            <th width="8%">CFOP</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbody-itens-devolucao"></tbody>
                                </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="step_trib">
                        <div class="totais-devolucao-box" id="box-totais-tributacao">
                            <div class="row">
                                <div class="col-md-2">Produtos: <span class="valor" id="tot_produtos">0,00</span></div>
                                <div class="col-md-2">ICMS: <span class="valor" id="tot_icms">0,00</span></div>
                                <div class="col-md-2">IPI: <span class="valor" id="tot_ipi">0,00</span></div>
                                <div class="col-md-2">ST: <span class="valor" id="tot_st">0,00</span></div>
                                <div class="col-md-2">Créd. SN: <span class="valor" id="tot_cred_sn">0,00</span></div>
                                <div class="col-md-2">Total NF: <span class="valor" id="tot_nf">0,00</span></div>
                            </div>
                        </div>
                        <div class="panel panel-primary">
                            <div class="panel-heading"><h3 class="panel-title">Tributação dos produtos</h3></div>
                            <div class="panel-body" id="painel-tributos-itens"></div>
                        </div>
                        <p class="text-muted"><i class="fa fa-info-circle"></i> Os tributos são carregados da NF de origem (proporcional à quantidade). Ajuste bases, alíquotas e valores conforme necessário. Ao avançar, a NF de devolução será gravada.</p>
                    </div>

                    <div id="step_val">
                        <div class="panel panel-primary">
                            <div class="panel-heading"><h3 class="panel-title">Validação contábil</h3></div>
                            <div class="panel-body text-center">
                                <p>Baixe o XML e o espelho (PDF) para validação com a contabilidade antes de salvar ou emitir.</p>
                                <p><strong>NF Devolução ID:</strong> <span id="lbl_id_nf_dev">-</span></p>
                                <button type="button" class="btn btn-info btn-sm btn-wizard-acao" id="btn_baixar_xml"><i class="fa fa-download"></i> Baixar XML</button>
                                <button type="button" class="btn btn-primary btn-sm btn-wizard-acao" id="btn_visualizar_espelho"><i class="fa fa-file-pdf-o"></i> Visualizar Espelho</button>
                                <button type="button" class="btn btn-default btn-sm btn-wizard-acao" id="btn_regenerar_espelho"><i class="fa fa-refresh"></i> Regenerar</button>
                                <div id="msg_espelho" class="small text-muted" style="margin-top:15px;"></div>
                            </div>
                        </div>
                    </div>

                    <div id="step_fim">
                        <div class="panel panel-primary">
                            <div class="panel-heading"><h3 class="panel-title">Salvar / Emitir</h3></div>
                            <div class="panel-body text-center">
                                <div id="resumo-final-devolucao"></div>
                                <br>
                                <button type="button" class="btn btn-success btn-sm btn-wizard-acao" id="btn_confirmar_devolucao"><i class="fa fa-save"></i> Salvar</button>
                                <button type="button" class="btn btn-warning btn-sm btn-wizard-acao" id="btn_emitir_nfe"><i class="fa fa-send"></i> Emitir NFe</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{include file="template/form.inc"}
<script type="text/javascript">
var PATH_CLIENTE = '{$pathCliente}';
var combosTributacao = {$combosTributacaoJson nofilter};
var opcoesTPNF = {$opcoesTPNFJson nofilter};
</script>
<form name="lancamento" id="lancamento" style="display:none;">
    <input type="hidden" name="form" value="nota_fiscal_devolucao">
    <input type="hidden" name="pessoa" value="">
    <input type="hidden" name="nome" value="">
    <input type="hidden" name="fornecedor" value="">
</form>
<script type="text/javascript" src="{$pathSweet}/dist/sweetalert2.all.min.js"></script>
<script src="{$bootstrap}/jQuery-Smart-Wizard/js/jquery.smartWizard.js"></script>
<script type="text/javascript" src="{$pathJs}/est/s_nota_fiscal_devolucao.js"></script>
<script>
    $(document).ready(function() {
        iniciarWizardDevolucao();
    });
</script>
