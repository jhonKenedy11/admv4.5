<!-- jQuery Inputmask para máscaras de campos -->
<script type="text/javascript" src="{$bootstrap}/jquery.inputmask/dist/jquery.inputmask.bundle.js"></script>
<script type="text/javascript" src="{$pathJs}/est/s_modal_servicos.js"> </script>
<script type="text/javascript" src="{$pathJs}/est/s_parcelas_servicos.js"> </script>
<link href="{$bootstrap}/jQuery-Smart-Wizard/styles/smart_wizard.css" rel="stylesheet">
<script src="{$bootstrap}/jQuery-Smart-Wizard/js/jquery.smartWizard.js"></script>
<script type="text/javascript" src="{$pathSweet}/dist/sweetalert2.all.min.js"></script>



<!-- Configuracao de estilos extra para o smart wizard 
cliente/custom.css
/** jQuery Smart Wizard  **/ 
-->

<!-- Modal Serviços -->
<div class="modal fade" id="modalServicos" tabindex="-1" role="dialog" aria-labelledby="modalServicosLabel" data-backdrop="static">
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
                                            <input type="text" class="form-control" name="prestador_empresa_nome" id="prestador_empresa_nome" maxlength="14" value="" readonly>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-form-label col-md-3 col-sm-3 label-align"> CNPJ </label>
                                        <div class="col-md-6 col-sm-6">
                                            <input type="text" class="form-control" name="prestador_cnpj_formatado" id="prestador_cnpj_formatado" maxlength="14" value="" readonly>
                                            <input  name="prestador_cnpj" id="prestador_cnpj" value="" hidden>
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
                                            <input type="text" class="form-control" name="prestador_serie" id="prestador_serie" maxlength="14" readonly>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group row">
                                        <label class="col-form-label col-md-3 col-sm-3 label-align"> Data Emissao </label>
                                        <div class="col-md-6 col-sm-6">
                                            <input type="text" class="form-control" name="prestador_data_emissao" id="prestador_data_emissao" maxlength="100" value="" readonly>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-form-label col-md-3 col-sm-3 label-align"> Data do Fato Gerador </label>
                                        <div class="col-md-6 col-sm-6">
                                            <input type="text" class="form-control" name="prestador_data_fato_gerador" id="prestador_data_fato_gerador" maxlength="100"  value="" readonly>
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
                                            <input type="text" class="form-control" name="tomador_tipo_pessoa" id="tomador_tipo_pessoa" maxlength="" readonly>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group row">
                                        <label class="col-form-label col-md-3 col-sm-3 label-align">CPF/CNPJ <span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6">
                                            <input type="text" class="form-control" name="tomador_cpfcnpj_formatado" id="tomador_cpfcnpj_formatado" maxlength="14" readonly>
                                            <input type="text" name="tomador_cpfcnpj" id="tomador_cpfcnpj" value="" hidden>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group row">
                                        <label class="col-form-label col-md-3 col-sm-3 label-align">Inscrição Estadual</label>
                                        <div class="col-md-6 col-sm-6">
                                            <input type="text" class="form-control" name="tomador_inscricao_estadual_rg" id="tomador_inscricao_estadual_rg" maxlength="16">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group row">
                                        <label class="col-form-label col-md-3 col-sm-3 label-align">Endereço Informado</label>
                                        <div class="col-md-6 col-sm-6">
                                            <select class="form-control" name="tomador_endereco_informado" id="tomador_endereco_informado" readonly>
                                                <option value="S">Sim</option>
                                                <option value="N">Não</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group row">
                                        <label class="col-form-label col-md-3 col-sm-3 label-align">Nome/Razão Social <span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6">
                                            <input type="text" class="form-control" name="tomador_razao_social" id="tomador_razao_social" maxlength="100" readonly>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group row">
                                        <label class="col-form-label col-md-3 col-sm-3 label-align">Sobrenome/Nome Fantasia</label>
                                        <div class="col-md-6 col-sm-6">
                                            <input type="text" class="form-control" name="tomador_nome_fantasia" id="tomador_nome_fantasia" maxlength="100" readonly>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group row">
                                        <label class="col-form-label col-md-3 col-sm-3 label-align">Email</label>
                                        <div class="col-md-6 col-sm-6">
                                            <input type="email" class="form-control" name="tomador_email" id="tomador_email" maxlength="100">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group row">
                                        <label class="col-form-label col-md-3 col-sm-3 label-align">País</label>
                                        <div class="col-md-6 col-sm-6">
                                            <input type="text" class="form-control" name="tomador_pais" id="tomador_pais" maxlength="100" value="" readonly>
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
                                            <input type="text" class="form-control" name="tomador_logradouro" id="tomador_logradouro" maxlength="70">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group row">
                                        <label class="col-form-label col-md-3 col-sm-3 label-align">Número</label>
                                        <div class="col-md-2 col-sm-2">
                                            <input type="text" class="form-control" name="tomador_numero_residencia" id="tomador_numero_residencia" maxlength="8">
                                        </div>

                                        <label class="col-form-label col-md-1 col-sm-1 label-align">Complemento</label>
                                        <div class="col-md-3 col-sm-3">
                                            <input type="text" class="form-control" name="tomador_complemento" id="tomador_complemento" maxlength="50">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group row">

                                        <label class="col-form-label col-md-3 col-sm-3 label-align">Bairro</label>
                                        <div class="col-md-3 col-sm-3">
                                            <input type="text" class="form-control" name="tomador_bairro" id="tomador_bairro" maxlength="30">
                                        </div>

                                        <label class="col-form-label col-md-1 col-sm-1 label-align">Cidade</label>
                                        <div class="col-md-2 col-sm-2">
                                            <input type="text" class="form-control" name="tomador_cidade" id="tomador_cidade" maxlength="9">
                                        </div>

                                    </div>
                                    
                                    <div class="form-group row">
                                        <label class="col-form-label col-md-3 col-sm-3 label-align">Ponto de Referência</label>
                                        <div class="col-md-6 col-sm-6">
                                            <input type="text" class="form-control" name="tomador_ponto_referencia" id="tomador_ponto_referencia" maxlength="100">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group row">
                                        
                                        <label class="col-form-label col-md-3 col-sm-3 label-align">CEP</label>
                                        <div class="col-md-3 col-sm-3">
                                            <input type="text" class="form-control" name="tomador_cep" id="tomador_cep" maxlength="8" readonly>
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
                                                <input type="text" class="form-control" name="tomador_ddd_fone_comercial" id="tomador_ddd_fone_comercial" maxlength="3" style="width: 60px;">
                                                <span class="input-group-addon">Fone</span>
                                                <input type="text" class="form-control" name="tomador_fone_comercial" id="tomador_fone_comercial" maxlength="9">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group row">
                                        <label class="col-form-label col-md-3 col-sm-3 label-align">Telefone Residencial</label>
                                        <div class="col-md-6 col-sm-6">
                                            <div class="input-group">
                                                <span class="input-group-addon">DDD</span>
                                                <input type="text" class="form-control" name="tomador_ddd_fone_residencial" id="tomador_ddd_fone_residencial" maxlength="3" style="width: 60px;">
                                                <span class="input-group-addon">Fone</span>
                                                <input type="text" class="form-control" name="tomador_fone_residencial" id="tomador_fone_residencial" maxlength="9">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group row">
                                        <label class="col-form-label col-md-3 col-sm-3 label-align">Fax</label>
                                        <div class="col-md-6 col-sm-6">
                                            <div class="input-group">
                                                <span class="input-group-addon">DDD</span>
                                                <input type="text" class="form-control" name="tomador_ddd_fax" id="tomador_ddd_fax" maxlength="3" style="width: 60px;">
                                                <span class="input-group-addon">Fax</span>
                                                <input type="text" class="form-control" name="tomador_fone_fax" id="tomador_fone_fax" maxlength="9">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Estado (apenas para estrangeiros) -->
                                    <div class="form-group row" id="divEstado" style="display: none;">
                                        <label class="col-form-label col-md-3 col-sm-3 label-align">Estado</label>
                                        <div class="col-md-6 col-sm-6">
                                            <input type="text" class="form-control" name="tomador_estado" id="tomador_estado" maxlength="100">
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
                                    <i class="fa fa-file-code-o"></i> Dados do Serviço
                                </h3>
                            </div>
                            <div class="panel-body">
                                <!-- Estado -->
                                <div class="form-group row">
                                    <label class="col-form-label col-md-2 col-sm-2 label-align text-danger">Estado *</label>
                                    <div class="col-md-4 col-sm-4">
                                        <select class="form-control" name="estado" id="estado" required>
                                            <option value="">Selecione um estado...</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Local da Prestação -->
                                <div class="form-group row">
                                    <label class="col-form-label col-md-2 col-sm-2 label-align text-danger">Local da Prestação *</label>
                                    <div class="col-md-10 col-sm-10">
                                        <select class="form-control select2" name="local_prestacao" id="local_prestacao" required>
                                            <option value="">Digite para buscar cidades...</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Lista de Serviço -->
                                <div class="form-group row">
                                    <label class="col-form-label col-md-2 col-sm-2 label-align text-danger">Lista de Serviço *</label>
                                    <div class="col-md-10 col-sm-10">
                                        <select class="form-control" name="lista_servico" id="lista_servico" disabled>
                                            <option value="">Selecione primeiro uma cidade</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Alíquota -->
                                <div class="form-group row">

                                    <div class="col-md-4 col-sm-4">
                                        <label class="text-danger">Situação Tributária *</label>
                                            <select class="form-control" name="situacao_tributaria" id="situacao_tributaria" disabled>
                                                <option value="">Selecione primeiro uma cidade</option>
                                            </select>
                                    </div>
                                
                                    <div class="col-md-2 col-sm-2">
                                        <label class="text-danger">Valor do Serviço *</label>
                                        <input type="text" class="form-control text-right" name="valor_servico" id="valor_servico" value="0,00" required>
                                    </div>

                                    <div class="col-md-1 col-sm-1 offset-md-1 offset-sm-1"></div>

                                    <div class="col-md-2 col-sm-2">
                                        <label class="text-danger"> Desc. Incondicional * </label>
                                        <input type="text" class="form-control text-right" name="desc_incondicional" id="desc_incondicional" value="0,00">
                                    </div>

                                    <div class="col-md-1 col-sm-1 offset-md-1 offset-sm-1"></div>

                                    <div class="col-md-2 col-sm-2">
                                        <label> Valor da Dedução </label>
                                        <div class="input-group">
                                            <input type="text" class="form-control text-right" name="valor_deducao" id="valor_deducao" value="0,00" readonly required>
                                            <span class="input-group-addon">
                                                <i class="fa fa-info-circle text-purple" data-toggle="tooltip" title="Valor a ser descontado na base de calculo para o ISS. E habilitado somente para as seguintes tributacoes: TRBC, TRBCRF, e TRBCST."></i>
                                            </span>
                                        </div>
                                    </div>

                                </div>

                                <!-- Base de Cálculo e Impostos -->
                                <div class="form-group row">


                                    <div class="col-md-4 col-sm-4">
                                        <label>Base de Cálculo</label>
                                        <input type="text" class="form-control text-right" name="base_calculo" id="base_calculo" value="0,00" readonly>
                                    </div>

                                    <div class="col-md-1 col-sm-1 offset-md-1 offset-sm-1"></div>

                                    <div class="col-md-2 col-sm-2">
                                        <label> Alíquota </label>
                                        <input type="text" class="form-control text-right" name="aliquota" id="aliquota" value="0,0000" readonly>
                                    </div>

                                    <div class="col-md-1 col-sm-1 offset-md-1 offset-sm-1"></div>

                                    <div class="col-md-2 col-sm-2">
                                        <label> ISSQN </label>
                                        <input type="text" class="form-control text-right" name="issqn" id="issqn" value="0,00" readonly>
                                    </div>

                                    <div class="col-md-1 col-sm-1 offset-md-1 offset-sm-1"></div>

                                    <div class="col-md-2 col-sm-2">
                                        <label>ISSRF</label>
                                        <input type="text" class="form-control text-right" name="issrf" id="issrf" value="0,00" readonly>
                                    </div>
                                </div>

                                <!-- Descrição -->
                                <div class="form-group row">

                                    <div class="col-md-12 col-sm-12">
                                        <label class="text-danger">Descrição * <small id="caracteres-restantes">(200 caracteres restantes)</small></label>
                                        <textarea class="form-control" name="descricao" id="descricao" rows="4" placeholder="Digite a descrição do serviço..." required onkeyup="validarDescricao(this)"></textarea>
                                    </div>

                                </div>

                                <!-- Botões de Controle -->
                                {* <div class="form-group row">
                                    <div class="col-md-12 text-right">
                                        <button type="button" class="btn btn-danger" id="btn_remover_item">
                                            <i class="fa fa-minus"></i>
                                        </button>
                                        <button type="button" class="btn btn-success" id="btn_adicionar_item">
                                            <i class="fa fa-plus"></i>
                                        </button>
                                    </div>
                                </div> *}
                            </div>
                        </div>

                        <!-- Lista servicos -->
                        <div class="panel panel-primary panel_servicos">
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
                                                <input type="text" class="form-control text-right" name="valor_total_final" id="valor_total_final" value="" readonly>
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
                                        
                                        <div class="form-group row">
                                            <label class="col-form-label col-md-6 col-sm-6 label-align">Número de Parcelas</label>
                                            <div class="col-md-6 col-sm-6">
                                                <select class="form-control" name="numero_parcelas" id="numero_parcelas">
                                                    <option value="1">1x</option>
                                                    <option value="2">2x</option>
                                                    <option value="3">3x</option>
                                                    <option value="4">4x</option>
                                                    <option value="5">5x</option>
                                                    <option value="6">6x</option>
                                                    <option value="7">7x</option>
                                                    <option value="8">8x</option>
                                                    <option value="9">9x</option>
                                                    <option value="10">10x</option>
                                                    <option value="11">11x</option>
                                                    <option value="12">12x</option>
                                                    <option value="13">13x</option>
                                                    <option value="14">14x</option>
                                                    <option value="15">15x</option>
                                                    <option value="16">16x</option>
                                                    <option value="17">17x</option>
                                                    <option value="18">18x</option>
                                                    <option value="19">19x</option>
                                                    <option value="20">20x</option>
                                                    <option value="21">21x</option>
                                                    <option value="22">22x</option>
                                                    <option value="23">23x</option>
                                                    <option value="24">24x</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Coluna Direita - Informações Complementares -->
                                    <div class="col-md-6">
                                        
                                        
                                        <div class="form-group row">
                                            <label class="col-form-label col-md-6 col-sm-6 label-align">Observações</label>
                                            <div class="col-md-6 col-sm-6">
                                                <textarea class="form-control" name="observacoes" id="observacoes" rows="3" maxlength="500" placeholder="Observações adicionais..."></textarea>
                                            </div>
                                        </div>
                                        
                                        {* <div class="form-group row">
                                            <label class="col-form-label col-md-6 col-sm-6 label-align">Data de Vencimento</label>
                                            <div class="col-md-6 col-sm-6">
                                                <input type="date" class="form-control" name="data_vencimento" id="data_vencimento">
                                            </div>
                                        </div> *}
                                    </div>
                                    
                                    <div class="col-md-12">
                                        <div class="form-group row">
                                            <button type="button" class="btn btn-success btn-sm" id="btnEmitirNFS">
                                            <i class="fa fa-check"></i> Emitir NFS-e
                                            </button>
                                            <button type="button" class="btn btn-info btn-sm" id="btnVisualizar">
                                                <i class="fa fa-eye"></i> Visualizar
                                            </button>
                                            <button type="button" class="btn btn-warning btn-sm" id="btnLimpar">
                                                <i class="fa fa-eraser"></i> Limpar
                                            </button>
                                        </div>

                                    </div>
                                </div>

                            </div>
                        </div>
                        
                        <!-- Seção de Parcelas -->
                        <div class="panel panel-primary" style="margin-top: 15px;">

                            <div class="panel-heading">
                                <h3 class="panel-title">
                                    <i class="fa fa-credit-card"></i> Parcelas de Pagamento
                                </h3>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <div class="table-responsive">
                                            <table id="tabela-parcelas" class="table table-bordered jambo_table table-striped">
                                                <thead>
                                                    <tr style="background: gray; color: white;">
                                                        <th width="8%">Parcela</th>
                                                        <th width="15%">Data Vencimento</th>
                                                        <th width="15%">Valor</th>
                                                        <th width="20%">Tipo Documento</th>
                                                        <th width="20%">Conta Recebimento</th>
                                                        <th width="15%">Situação</th>
                                                        <th width="7%">Obs</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="tbody-parcelas">
                                                    <!-- Parcelas serão carregadas via AJAX -->
                                                    <tr>
                                                        <td colspan="7" class="text-center">
                                                            <i class="fa fa-spinner fa-spin"></i> Carregando parcelas...
                                                        </td>
                                                    </tr>
                                                    
                                                    <!-- Exemplo de estrutura que será gerada via JavaScript:
                                                    <tr>
                                                        <td>1</td>
                                                        <td>
                                                            <input class="form-control" type="text" name="venc1" value="15/12/2024">
                                                        </td>
                                                        <td>
                                                            <input class="form-control text-right" type="text" name="valor1" value="1.000,00">
                                                        </td>
                                                        <td>
                                                            <select name="tipo1" class="form-control">
                                                                <option value="">Selecione...</option>
                                                                <option value="1">Boleto</option>
                                                                <option value="2">Cartão</option>
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <select name="conta1" class="form-control">
                                                                <option value="">Selecione...</option>
                                                                <option value="1">Conta Corrente</option>
                                                                <option value="2">Poupança</option>
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <select name="situacao1" class="form-control">
                                                                <option value="">Selecione...</option>
                                                                <option value="1">Pendente</option>
                                                                <option value="2">Pago</option>
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <input class="form-control" type="text" name="obs1" value="">
                                                        </td>
                                                    </tr>
                                                    -->
                                                </tbody>
                                            </table>
                                        </div>
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

<script src="{$bootstrap}/select2-master/dist/js/select2.full.min.js"></script>

