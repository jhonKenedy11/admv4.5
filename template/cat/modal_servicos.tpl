<script type="text/javascript" src="{$pathJs}/cat/s_modal_servicos.js"> </script>
<link href="{$bootstrap}/jQuery-Smart-Wizard/styles/smart_wizard.css" rel="stylesheet">
<script src="{$bootstrap}/jQuery-Smart-Wizard/js/jquery.smartWizard.js"></script>

<!-- Configuracao de estilos extra para o smart wizard 
cliente/custom.css
/** jQuery Smart Wizard  **/ 
-->

<!-- Modal Serviços -->
<div class="modal fade" id="modalServicos" tabindex="-1" role="dialog" aria-labelledby="modalServicosLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-lg" role="document" style="width: 90% !important;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="modalServicosLabel">
                    <i class="fa fa-list"></i> Emissao de NFS
                </h4>
            </div>
            <div class="modal-body">
                <!-- Smart Wizard -->
                <div id="wizard" class="form_wizard wizard_horizontal">
                    <ul class="wizard_steps">
                        <li>
                            <a href="#step_1">
                                <span class="step_no"><span class="glyphicon glyphicon-user" aria-hidden="true"></span></span>
                                <small>Dados do Prestador</small>
                            </a>
                        </li>
                        <li>
                            <a href="#step_2">
                                <span class="step_no"><span class="glyphicon glyphicon-user" aria-hidden="true"></span></span>
                                <small>Dados do Tomador</small>
                            </a>
                        </li>
                        <li>
                            <a href="#step_3">
                                <span class="step_no"><span class="glyphicon glyphicon-align-justify" aria-hidden="true"></span></span>
                                <small>Lista de Serviços</small>
                            </a>
                        </li>
                        <li>
                            <a href="#step_4">
                                <span class="step_no"><span class="glyphicon glyphicon-usd" aria-hidden="true"></span></span>
                                <small>Valores e Informações</small>
                            </a>
                        </li>
                    </ul>
                    
                    <!-- Step 1: Dados do Prestador -->
                    <div id="step_1">
                        <form class="form-horizontal form-label-left">
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    <h3 class="panel-title">
                                        <i class="fa fa-building"></i> Dados do Prestador
                                    </h3>
                                </div>
                                <div class="panel-body">

                                    <div class="form-group row">
                                        <label class="col-form-label col-md-3 col-sm-3 label-align"> Empresa </label>
                                        <div class="col-md-6 col-sm-6">
                                            <select class="form-control" name="tipo" id="tipo" required>
                                                <option value="">Selecione...</option>
                                                <option value="F">Empresa 1</option>
                                                <option value="J">Empresa 2</option>
                                                <option value="E">Empresa 3</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-form-label col-md-3 col-sm-3 label-align"> CNPJ </label>
                                        <div class="col-md-6 col-sm-6">
                                            <input type="text" class="form-control" name="prestador_cnpj" id="prestador_cnpj" maxlength="14" required>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group row">
                                        <label class="col-form-label col-md-3 col-sm-3 label-align"> Nome da Empresa </label>
                                        <div class="col-md-6 col-sm-6">
                                            <input type="text" class="form-control" name="prestador_nome" id="prestador_nome" maxlength="100" required>
                                        </div>
                                    </div>
                                
                                </div>
                            </div>


                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    <h3 class="panel-title">
                                        <i class="fa fa-file"></i> Dados Nota Fiscal
                                    </h3>
                                </div>
                                <div class="panel-body">
                                    <div class="form-group row">
                                        <label class="col-form-label col-md-3 col-sm-3 label-align"> Serie </label>
                                        <div class="col-md-6 col-sm-6">
                                            <input type="text" class="form-control" name="prestador_cnpj" id="prestador_serie" maxlength="14" required>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group row">
                                        <label class="col-form-label col-md-3 col-sm-3 label-align"> Data Emissao </label>
                                        <div class="col-md-6 col-sm-6">
                                            <input type="text" class="form-control" name="prestador_nome" id="prestador_data_emissao" maxlength="100" required>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-form-label col-md-3 col-sm-3 label-align"> Data do Fato Gerador </label>
                                        <div class="col-md-6 col-sm-6">
                                            <input type="text" class="form-control" name="prestador_nome" id="prestador_data_fato_gerador" maxlength="100" required>
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
                        </form>
                    </div>
                    
                    <!-- Step 2: Dados do Tomador -->
                    <div id="step_2">
                        <form class="form-horizontal form-label-left">
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    <h3 class="panel-title">
                                        <i class="fa fa-user"></i> Dados do Tomador
                                    </h3>
                                </div>
                                <div class="panel-body">
                                    <div class="form-group row">
                                        <label class="col-form-label col-md-3 col-sm-3 label-align">Tipo de Pessoa <span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6">
                                            <select class="form-control" name="tipo" id="tipo" required>
                                                <option value="">Selecione...</option>
                                                <option value="F">Física</option>
                                                <option value="J">Jurídica</option>
                                                <option value="E">Estrangeira</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group row">
                                        <label class="col-form-label col-md-3 col-sm-3 label-align">CPF/CNPJ <span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6">
                                            <input type="text" class="form-control" name="cpfcnpj" id="cpfcnpj" maxlength="14" required>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group row">
                                        <label class="col-form-label col-md-3 col-sm-3 label-align">Inscrição Estadual</label>
                                        <div class="col-md-6 col-sm-6">
                                            <input type="text" class="form-control" name="ie" id="ie" maxlength="16">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group row">
                                        <label class="col-form-label col-md-3 col-sm-3 label-align">Endereço Informado</label>
                                        <div class="col-md-6 col-sm-6">
                                            <select class="form-control" name="endereco_informado" id="endereco_informado">
                                                <option value="S">Sim</option>
                                                <option value="N">Não</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group row">
                                        <label class="col-form-label col-md-3 col-sm-3 label-align">Nome/Razão Social <span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6">
                                            <input type="text" class="form-control" name="nome_razao_social" id="nome_razao_social" maxlength="100" required>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group row">
                                        <label class="col-form-label col-md-3 col-sm-3 label-align">Sobrenome/Nome Fantasia</label>
                                        <div class="col-md-6 col-sm-6">
                                            <input type="text" class="form-control" name="sobrenome_nome_fantasia" id="sobrenome_nome_fantasia" maxlength="100">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group row">
                                        <label class="col-form-label col-md-3 col-sm-3 label-align">Email</label>
                                        <div class="col-md-6 col-sm-6">
                                            <input type="email" class="form-control" name="email" id="email" maxlength="100">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group row">
                                        <label class="col-form-label col-md-3 col-sm-3 label-align">País</label>
                                        <div class="col-md-6 col-sm-6">
                                            <input type="text" class="form-control" name="pais" id="pais" maxlength="100" value="BRASIL">
                                        </div>
                                    </div>
                                    
                                    <!-- Endereço -->
                                    <div class="form-group row">
                                        <div class="col-md-12">
                                            <h5><i class="fa fa-map-marker"></i> Endereço</h5>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group row">
                                        <label class="col-form-label col-md-3 col-sm-3 label-align">Logradouro</label>
                                        <div class="col-md-6 col-sm-6">
                                            <input type="text" class="form-control" name="logradouro" id="logradouro" maxlength="70">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group row">
                                        <label class="col-form-label col-md-3 col-sm-3 label-align">Número</label>
                                        <div class="col-md-2 col-sm-2">
                                            <input type="text" class="form-control" name="numero_residencia" id="numero_residencia" maxlength="8">
                                        </div>
                                        <label class="col-form-label col-md-1 col-sm-1 label-align">Complemento</label>
                                        <div class="col-md-3 col-sm-3">
                                            <input type="text" class="form-control" name="complemento" id="complemento" maxlength="50">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group row">
                                        <label class="col-form-label col-md-3 col-sm-3 label-align">Bairro</label>
                                        <div class="col-md-3 col-sm-3">
                                            <input type="text" class="form-control" name="bairro" id="bairro" maxlength="30">
                                        </div>
                                        <label class="col-form-label col-md-1 col-sm-1 label-align">Cidade</label>
                                        <div class="col-md-2 col-sm-2">
                                            <input type="text" class="form-control" name="cidade" id="cidade" maxlength="9">
                                        </div>
                                        <label class="col-form-label col-md-1 col-sm-1 label-align">CEP</label>
                                        <div class="col-md-2 col-sm-2">
                                            <input type="text" class="form-control" name="cep" id="cep" maxlength="8">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group row">
                                        <label class="col-form-label col-md-3 col-sm-3 label-align">Ponto de Referência</label>
                                        <div class="col-md-6 col-sm-6">
                                            <input type="text" class="form-control" name="ponto_referencia" id="ponto_referencia" maxlength="100">
                                        </div>
                                    </div>
                                    
                                    <!-- Telefones -->
                                    <div class="form-group row">
                                        <div class="col-md-12">
                                            <h5><i class="fa fa-phone"></i> Telefones</h5>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group row">
                                        <label class="col-form-label col-md-3 col-sm-3 label-align">Telefone Comercial</label>
                                        <div class="col-md-6 col-sm-6">
                                            <div class="input-group">
                                                <span class="input-group-addon">DDD</span>
                                                <input type="text" class="form-control" name="ddd_fone_comercial" id="ddd_fone_comercial" maxlength="3" style="width: 60px;">
                                                <span class="input-group-addon">Fone</span>
                                                <input type="text" class="form-control" name="fone_comercial" id="fone_comercial" maxlength="9">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group row">
                                        <label class="col-form-label col-md-3 col-sm-3 label-align">Telefone Residencial</label>
                                        <div class="col-md-6 col-sm-6">
                                            <div class="input-group">
                                                <span class="input-group-addon">DDD</span>
                                                <input type="text" class="form-control" name="ddd_fone_residencial" id="ddd_fone_residencial" maxlength="3" style="width: 60px;">
                                                <span class="input-group-addon">Fone</span>
                                                <input type="text" class="form-control" name="fone_residencial" id="fone_residencial" maxlength="9">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group row">
                                        <label class="col-form-label col-md-3 col-sm-3 label-align">Fax</label>
                                        <div class="col-md-6 col-sm-6">
                                            <div class="input-group">
                                                <span class="input-group-addon">DDD</span>
                                                <input type="text" class="form-control" name="ddd_fax" id="ddd_fax" maxlength="3" style="width: 60px;">
                                                <span class="input-group-addon">Fax</span>
                                                <input type="text" class="form-control" name="fone_fax" id="fone_fax" maxlength="9">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Estado (apenas para estrangeiros) -->
                                    <div class="form-group row" id="divEstado" style="display: none;">
                                        <label class="col-form-label col-md-3 col-sm-3 label-align">Estado</label>
                                        <div class="col-md-6 col-sm-6">
                                            <input type="text" class="form-control" name="estado" id="estado" maxlength="100">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    
                    <!-- Step 3: Lista de Itens/Serviços -->
                    <div id="step_3">

                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <h3 class="panel-title">
                                    <i class="fa fa-list"></i> Lista de Serviços
                                </h3>
                            </div>
                            <div class="panel-body">
                                <!-- Conteúdo será carregado via AJAX -->
                                <div class="text-center">
                                    <i class="fa fa-spinner fa-spin fa-2x"></i><br>
                                    Carregando serviços...
                                </div>
                            </div>
                        </div>


                        <!-- Lista servicos -->
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <h3 class="panel-title">
                                    <i class="fa fa-list"></i> Lista de Serviços
                                </h3>
                            </div>
                            <div class="panel-body">
                                <!-- Conteúdo será carregado via AJAX -->
                                <div class="text-center">
                                    <i class="fa fa-spinner fa-spin fa-2x"></i><br>
                                    Carregando serviços...
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Step 4: Valores e Informações Complementares -->
                    <div id="step_4">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <h3 class="panel-title">
                                    <i class="fa fa-calculator"></i> Valores e Informações Complementares
                                </h3>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <!-- Coluna Esquerda - Valores -->
                                    <div class="col-md-6">
                                        <h5><i class="fa fa-money"></i> Valores</h5>
                                        
                                        <div class="form-group row">
                                            <label class="col-form-label col-md-6 col-sm-6 label-align">Valor Total dos Serviços</label>
                                            <div class="col-md-6 col-sm-6">
                                                <input type="text" class="form-control text-right" name="valor_total_servicos" id="valor_total_servicos" readonly>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group row">
                                            <label class="col-form-label col-md-6 col-sm-6 label-align">Valor de Desconto</label>
                                            <div class="col-md-6 col-sm-6">
                                                <input type="text" class="form-control text-right" name="valor_desconto" id="valor_desconto" value="0,00">
                                            </div>
                                        </div>
                                        
                                        <div class="form-group row">
                                            <label class="col-form-label col-md-6 col-sm-6 label-align">Valor Total Final</label>
                                            <div class="col-md-6 col-sm-6">
                                                <input type="text" class="form-control text-right" name="valor_total_final" id="valor_total_final" readonly>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group row">
                                            <label class="col-form-label col-md-6 col-sm-6 label-align">Forma de Pagamento</label>
                                            <div class="col-md-6 col-sm-6">
                                                <select class="form-control" name="forma_pagamento" id="forma_pagamento">
                                                    <option value="">Selecione...</option>
                                                    <option value="1">Dinheiro</option>
                                                    <option value="2">Cheque</option>
                                                    <option value="3">Cartão de Crédito</option>
                                                    <option value="4">Cartão de Débito</option>
                                                    <option value="5">Transferência Bancária</option>
                                                    <option value="6">Boleto Bancário</option>
                                                    <option value="7">PIX</option>
                                                    <option value="99">Outros</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Coluna Direita - Informações Complementares -->
                                    <div class="col-md-6">
                                        <h5><i class="fa fa-info-circle"></i> Informações Complementares</h5>
                                        
                                        <div class="form-group row">
                                            <label class="col-form-label col-md-6 col-sm-6 label-align">Natureza da Operação</label>
                                            <div class="col-md-6 col-sm-6">
                                                <select class="form-control" name="natureza_operacao" id="natureza_operacao">
                                                    <option value="">Selecione...</option>
                                                    <option value="1">Prestação de Serviços</option>
                                                    <option value="2">Venda de Mercadorias</option>
                                                    <option value="3">Serviços de Transporte</option>
                                                    <option value="4">Serviços de Telecomunicação</option>
                                                    <option value="5">Serviços de Informática</option>
                                                    <option value="99">Outros</option>
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group row">
                                            <label class="col-form-label col-md-6 col-sm-6 label-align">Regime de Tributação</label>
                                            <div class="col-md-6 col-sm-6">
                                                <select class="form-control" name="regime_tributacao" id="regime_tributacao">
                                                    <option value="">Selecione...</option>
                                                    <option value="1">Simples Nacional</option>
                                                    <option value="2">Lucro Presumido</option>
                                                    <option value="3">Lucro Real</option>
                                                    <option value="4">MEI</option>
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group row">
                                            <label class="col-form-label col-md-6 col-sm-6 label-align">Observações</label>
                                            <div class="col-md-6 col-sm-6">
                                                <textarea class="form-control" name="observacoes" id="observacoes" rows="3" maxlength="500" placeholder="Observações adicionais..."></textarea>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group row">
                                            <label class="col-form-label col-md-6 col-sm-6 label-align">Data de Vencimento</label>
                                            <div class="col-md-6 col-sm-6">
                                                <input type="date" class="form-control" name="data_vencimento" id="data_vencimento">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Botões de Ação -->
                                <div class="row mt-3">
                                    <div class="col-md-12 text-center">
                                        <button type="button" class="btn btn-success" id="btnEmitirNFS">
                                            <i class="fa fa-check"></i> Emitir NFS-e
                                        </button>
                                        <button type="button" class="btn btn-info" id="btnVisualizar">
                                            <i class="fa fa-eye"></i> Visualizar
                                        </button>
                                        <button type="button" class="btn btn-warning" id="btnLimpar">
                                            <i class="fa fa-eraser"></i> Limpar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End SmartWizard Content -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <i class="fa fa-times"></i> Fechar
                </button>
            </div>
        </div>
    </div>
</div>
