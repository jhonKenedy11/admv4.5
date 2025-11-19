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
                                            <input type="text" class="form-control input-sm" name="prestador_empresa_nome" id="prestador_empresa_nome" maxlength="14" value="" readonly>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-form-label col-md-3 col-sm-3 label-align"> CNPJ </label>
                                        <div class="col-md-6 col-sm-6">
                                            <input type="text" class="form-control input-sm" name="prestador_cnpj_formatado" id="prestador_cnpj_formatado" maxlength="14" value="" readonly>
                                            <input  name="prestador_cnpj" id="prestador_cnpj" value="" hidden>
                                            <input  name="prestador_codigo_municipio" id="prestador_codigo_municipio" value="" hidden>
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
                                            <input type="text" class="form-control input-sm" name="prestador_serie" id="prestador_serie" maxlength="14" readonly>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-form-label col-md-3 col-sm-3 label-align"> Data do Fato Gerador </label>
                                        <div class="col-md-6 col-sm-6">
                                            <input type="text" class="form-control input-sm" name="prestador_data_fato_gerador" id="prestador_data_fato_gerador" maxlength="100"  value="" readonly>
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
                                            <input type="text" class="form-control input-sm" name="tomador_tipo_pessoa_desc" id="tomador_tipo_pessoa_desc" maxlength="" readonly>
                                            <input type="text" name="tomador_tipo_pessoa" id="tomador_tipo_pessoa" value="" hidden>
                                            <input type="number" name="tomador_id" id="tomador_id" value="" hidden>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group row">
                                        <label class="col-form-label col-md-3 col-sm-3 label-align">CPF/CNPJ <span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6">
                                            <input type="text" class="form-control input-sm" name="tomador_cpfcnpj_formatado" id="tomador_cpfcnpj_formatado" maxlength="14" readonly>
                                            <input type="text" name="tomador_cpfcnpj" id="tomador_cpfcnpj" value="" hidden>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group row">
                                        <label class="col-form-label col-md-3 col-sm-3 label-align">Inscrição Estadual</label>
                                        <div class="col-md-6 col-sm-6">
                                            <input type="text" class="form-control input-sm" name="tomador_inscricao_estadual_rg" id="tomador_inscricao_estadual_rg" maxlength="16">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group row">
                                        <label class="col-form-label col-md-3 col-sm-3 label-align">Endereço Informado</label>
                                        <div class="col-md-6 col-sm-6">
                                            <select class="form-control input-sm" name="tomador_endereco_informado" id="tomador_endereco_informado" readonly>
                                                <option value="S">Sim</option>
                                                <option value="N">Não</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group row">
                                        <label class="col-form-label col-md-3 col-sm-3 label-align">Nome/Razão Social <span class="required">*</span></label>
                                        <div class="col-md-6 col-sm-6">
                                            <input type="text" class="form-control input-sm" name="tomador_razao_social" id="tomador_razao_social" maxlength="100" readonly>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group row">
                                        <label class="col-form-label col-md-3 col-sm-3 label-align">Sobrenome/Nome Fantasia</label>
                                        <div class="col-md-6 col-sm-6">
                                            <input type="text" class="form-control input-sm" name="tomador_nome_fantasia" id="tomador_nome_fantasia" maxlength="100" readonly>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group row">
                                        <label class="col-form-label col-md-3 col-sm-3 label-align">Email</label>
                                        <div class="col-md-6 col-sm-6">
                                            <input type="email" class="form-control input-sm" name="tomador_email" id="tomador_email" maxlength="100">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group row">
                                        <label class="col-form-label col-md-3 col-sm-3 label-align">País</label>
                                        <div class="col-md-6 col-sm-6">
                                            <input type="text" class="form-control input-sm" name="tomador_pais" id="tomador_pais" maxlength="100" value="" readonly>
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
                                            <input type="text" class="form-control input-sm" name="tomador_logradouro" id="tomador_logradouro" maxlength="70">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group row">
                                        <label class="col-form-label col-md-3 col-sm-3 label-align">Número</label>
                                        <div class="col-md-2 col-sm-2">
                                            <input type="text" class="form-control input-sm" name="tomador_numero_residencia" id="tomador_numero_residencia" maxlength="8">
                                        </div>

                                        <label class="col-form-label col-md-1 col-sm-1 label-align">Complemento</label>
                                        <div class="col-md-3 col-sm-3">
                                            <input type="text" class="form-control input-sm" name="tomador_complemento" id="tomador_complemento" maxlength="50">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group row">

                                        <label class="col-form-label col-md-3 col-sm-3 label-align">Bairro</label>
                                        <div class="col-md-3 col-sm-3">
                                            <input type="text" class="form-control input-sm" name="tomador_bairro" id="tomador_bairro" maxlength="30">
                                        </div>

                                        <label class="col-form-label col-md-1 col-sm-1 label-align">Cidade</label>
                                        <div class="col-md-2 col-sm-2">
                                            <input type="text" class="form-control input-sm" name="tomador_cidade" id="tomador_cidade" maxlength="9">
                                            <input type="text" name="tomador_codigo_municipio" id="tomador_codigo_municipio" value="" hidden>
                                        </div>

                                    </div>
                                    
                                    <div class="form-group row">
                                        <label class="col-form-label col-md-3 col-sm-3 label-align">Ponto de Referência</label>
                                        <div class="col-md-6 col-sm-6">
                                            <input type="text" class="form-control input-sm" name="tomador_ponto_referencia" id="tomador_ponto_referencia" maxlength="100">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group row">
                                        
                                        <label class="col-form-label col-md-3 col-sm-3 label-align">CEP</label>
                                        <div class="col-md-3 col-sm-3">
                                            <input type="text" class="form-control input-sm" name="tomador_cep_formatado" id="tomador_cep_formatado" maxlength="8" readonly>
                                            <input type="text" name="tomador_cep" id="tomador_cep" value="" hidden>
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
                                                <input type="text" class="form-control input-sm" name="tomador_ddd_fone_comercial" id="tomador_ddd_fone_comercial" maxlength="3" style="width: 60px;">
                                                <span class="input-group-addon">Fone</span>
                                                <input type="text" class="form-control input-sm" name="tomador_fone_comercial" id="tomador_fone_comercial" maxlength="9">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group row">
                                        <label class="col-form-label col-md-3 col-sm-3 label-align">Telefone Residencial</label>
                                        <div class="col-md-6 col-sm-6">
                                            <div class="input-group">
                                                <span class="input-group-addon">DDD</span>
                                                <input type="text" class="form-control input-sm" name="tomador_ddd_fone_residencial" id="tomador_ddd_fone_residencial" maxlength="3" style="width: 60px;">
                                                <span class="input-group-addon">Fone</span>
                                                <input type="text" class="form-control input-sm" name="tomador_fone_residencial" id="tomador_fone_residencial" maxlength="9">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group row">
                                        <label class="col-form-label col-md-3 col-sm-3 label-align">Fax</label>
                                        <div class="col-md-6 col-sm-6">
                                            <div class="input-group">
                                                <span class="input-group-addon">DDD</span>
                                                <input type="text" class="form-control input-sm" name="tomador_ddd_fax" id="tomador_ddd_fax" maxlength="3" style="width: 60px;">
                                                <span class="input-group-addon">Fax</span>
                                                <input type="text" class="form-control input-sm" name="tomador_fone_fax" id="tomador_fone_fax" maxlength="9">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Estado (apenas para estrangeiros) -->
                                    <div class="form-group row" id="divEstado" style="display: none;">
                                        <label class="col-form-label col-md-3 col-sm-3 label-align">Estado</label>
                                        <div class="col-md-6 col-sm-6">
                                            <input type="text" class="form-control input-sm" name="tomador_estado" id="tomador_estado" maxlength="100">
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
                                        <select class="form-control input-sm" name="estado" id="estado" required>
                                            <option value="">Selecione um estado...</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Local da Prestação -->
                                <div class="form-group row">
                                    <label class="col-form-label col-md-2 col-sm-2 label-align text-danger">Local da Prestação *</label>
                                    <div class="col-md-10 col-sm-10">
                                        <select class="form-control select2 input-sm" name="local_prestacao" id="local_prestacao" required>
                                            <option value="">Digite para buscar cidades...</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Lista de Serviço -->
                                <div class="form-group row">
                                    <label class="col-form-label col-md-2 col-sm-2 label-align text-danger">Lista de Serviço *</label>
                                    <div class="col-md-10 col-sm-10">
                                        <select class="form-control input-sm" name="lista_servico" id="lista_servico">
                                            <option value="">Selecione um serviço</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Alíquota -->
                                <div class="form-group row">

                                    <div class="col-md-4 col-sm-4">
                                        <label class="text-danger">Situação Tributária *</label>
                                            <select class="form-control input-sm" name="situacao_tributaria" id="situacao_tributaria">
                                                <option value="">Selecione uma situação tributária</option>
                                            </select>
                                    </div>
                                
                                    <div class="col-md-2 col-sm-2">
                                        <label class="text-danger">Valor do Serviço *</label>
                                        <input type="text" class="form-control text-right input-sm" name="valor_servico" id="valor_servico" value="0,00" required>
                                    </div>

                                    <div class="col-md-1 col-sm-1 offset-md-1 offset-sm-1"></div>

                                    <div class="col-md-2 col-sm-2">
                                        <label class="text-danger"> Desc. Incondicional * </label>
                                        <input type="text" class="form-control text-right input-sm" name="desc_incondicional" id="desc_incondicional" value="0,00">
                                    </div>

                                    <div class="col-md-1 col-sm-1 offset-md-1 offset-sm-1"></div>

                                    <div class="col-md-2 col-sm-2">
                                        <label> Valor da Dedução </label>
                                        <div class="input-group">
                                            <input type="text" class="form-control text-right input-sm" name="valor_deducao" id="valor_deducao" value="0,00" readonly required>
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
                                        <input type="text" class="form-control text-right input-sm" name="base_calculo" id="base_calculo" value="0,00" readonly>
                                    </div>

                                    <div class="col-md-1 col-sm-1 offset-md-1 offset-sm-1"></div>

                                    <div class="col-md-2 col-sm-2">
                                        <label> Alíquota </label>
                                        <input type="text" class="form-control text-right input-sm" name="aliquota" id="aliquota" value="0,00">
                                    </div>

                                    <div class="col-md-1 col-sm-1 offset-md-1 offset-sm-1"></div>

                                    <div class="col-md-2 col-sm-2">
                                        <label> ISSQN </label>
                                        <input type="text" class="form-control text-right input-sm" name="issqn" id="issqn" value="0,00" readonly>
                                    </div>

                                    <div class="col-md-1 col-sm-1 offset-md-1 offset-sm-1"></div>

                                    <div class="col-md-2 col-sm-2">
                                        <label>ISSRF</label>

                                        <input type="text" class="form-control text-right input-sm" name="issrf" id="issrf" value="0,00" readonly>
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
                                    <!-- Primeira linha -->
                                    <div class="col-md-12">

                                        <div class="col-md-2">
                                            <div class="form-group mb-3">
                                                <label for="valor_inss" class="form-label small">INSS</label>
                                                <div class="input-group input-group-sm">
                                                    <input type="text" class="form-control text-right input-sm" name="valor_inss" id="valor_inss">
                                                    <span class="input-group-addon">
                                                        <i class="fa fa-info-circle text-purple" data-toggle="tooltip" 
                                                        title="Valor do INSS. Este valor não afetará a
                                                        base de cálculo do imposto, apenas assinala na nota."></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                
                                        <div class="col-md-2">
                                            <div class="form-group mb-3">
                                                <label for="valor_pis" class="form-label small"> PIS </label>
                                                <div class="input-group input-group-sm">
                                                    <input type="text" class="form-control text-right input-sm" name="valor_pis" id="valor_pis">
                                                    <span class="input-group-addon">
                                                        <i class="fa fa-info-circle text-purple" data-toggle="tooltip" 
                                                        title="Valor do PIS. Este valor não afetará a base de cálculo do imposto, apenas assinala na nota."></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group mb-3">
                                                <label for="valor_cofins" class="form-label small"> COFINS </label>
                                                <div class="input-group input-group-sm">
                                                    <input type="text" class="form-control text-right input-sm" name="valor_cofins" id="valor_cofins">
                                                    <span class="input-group-addon">
                                                        <i class="fa fa-info-circle text-purple" data-toggle="tooltip" 
                                                        title="Valor do Cofins. Este valor não afetará a base de cálculo do imposto, apenas assinala na nota."></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        

                                        <div class="col-md-2">
                                            <div class="form-group mb-3">
                                                <label for="valor_ir" class="form-label small"> IR </label>
                                                <div class="input-group input-group-sm">
                                                    <input type="text" class="form-control text-right input-sm" name="valor_ir" id="valor_ir">
                                                    <span class="input-group-addon">
                                                        <i class="fa fa-info-circle text-purple" data-toggle="tooltip" 
                                                        title="Valor do IRRF (Imposto de Renda Retido na Fonte). 
                                                        Este valor não afetará a base de cálculo do imposto, 
                                                        apenas assinala na nota."></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    

                                        <div class="col-md-2">
                                            <div class="form-group mb-3">
                                                <label for="valor_contribuicao_social" class="form-label small"> Contribuição Social</label>
                                                <div class="input-group input-group-sm">
                                                    <input type="text" class="form-control text-right input-sm" name="valor_contribuicao_social" id="valor_contribuicao_social">
                                                    <span class="input-group-addon">
                                                        <i class="fa fa-info-circle text-purple" data-toggle="tooltip" 
                                                        title="Valor da contribuição social. Este valor não afetará a base de cálculo do imposto, apenas assinala na nota."></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group mb-3">
                                                <label for="valor_total_aliquota" class="form-label small">Alíquota</label>
                                                <input type="text" class="form-control text-right input-sm" name="valor_total_aliquota" id="valor_total_aliquota" readonly>
                                            </div>
                                        </div>

                                    </div>

                                    <!-- Segunda linha -->
                                    <div class="col-md-12">

                                        <div class="col-md-2">
                                            <div class="form-group mb-3">
                                                <label for="valor_total_servicos" class="form-label small">Valor Total</label>
                                                <input type="text" class="form-control text-right input-sm" name="valor_total" id="valor_total_servicos" readonly>
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group mb-3">
                                                <label for="valor_total_deducao" class="form-label small">Valor Dedução</label>
                                                <input type="text" class="form-control text-right input-sm" name="valor_total_deducao" id="valor_total_deducao" readonly>
                                            </div>
                                        </div>


                                        <div class="col-md-2">
                                            <div class="form-group mb-3">
                                                <label for="valor_total_desconto" class="form-label small">Valor de Desconto</label>
                                                <input type="text" class="form-control text-right input-sm" name="valor_total_desconto" id="valor_total_desconto" value="0,00" readonly>
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group mb-3">
                                                <label for="valor_total_base_calculo" class="form-label small">Valor Base de Cálculo</label>
                                                <input type="text" class="form-control text-right input-sm" name="valor_total_base_calculo" id="valor_total_base_calculo" readonly>
                                            </div>
                                        </div>

                                        <div class="col-md-4 col-sm-4">
                                            <label class="form-label small">Parcelas</label>
                                            <select class="form-control input-sm" name="parcelas" id="parcelas"></select>
                                        </div>
                                        

                                    </div>

                                    <!-- Terceira linha -->
                                    <div class="col-md-12">


                                        <div class="col-md-12">
                                            <div class="form-group mb-3">
                                            <label class="label-align small">Observações</label>
                                            <textarea class="form-control" name="observacoes" id="observacoes" rows="3" maxlength="500" placeholder="Observações adicionais..."></textarea>
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
                <button type="button" class="btn btn-default pull-left btn-sm" data-dismiss="modal">
                    <i class="fa fa-times"></i> Fechar
                </button>

                <button type="button" class="btn btn-success pull-right" id="btnEmitirNFS">
                    <i class="fa fa-check"></i> Emitir NFS-e
                </button>
                {* <button type="button" class="btn btn-info btn-sm" id="btnVisualizar">
                    <i class="fa fa-eye"></i> Visualizar
                </button>
                <button type="button" class="btn btn-warning btn-sm" id="btnLimpar">
                    <i class="fa fa-eraser"></i> Limpar
                </button> *}
            </div>
        </div>
    </div>
</div>

<!-- Modal de Observação de Parcela -->
<div class="modal fade" id="modalObservacaoParcela" tabindex="-1" role="dialog" aria-labelledby="modalObservacaoParcelaLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="modalObsParcelaTitulo">
                    <i class="fa fa-comment"></i> Observação da Parcela
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Fechar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="modalObsParcelaNumero">
                <div class="form-group">
                    <label for="modalObsParcelaTexto">
                        <i class="fa fa-pencil"></i> Digite a observação:
                    </label>
                    <textarea class="form-control" id="modalObsParcelaTexto" rows="5" 
                              placeholder="Digite aqui a observação para esta parcela..." 
                              maxlength="500"></textarea>
                    <small class="form-text text-muted">
                        Limite de 500 caracteres
                    </small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fa fa-times"></i> Fechar
                </button>
            </div>
        </div>
    </div>
</div>

<script src="{$bootstrap}/select2-master/dist/js/select2.full.min.js"></script>

