        <!-- page content -->
        <div class="right_col" role="main">      
      <div class="">

        <div class="page-title">
          <div class="title_left">
                  <h3>Parametros Sistema</h3>
          </div>
        </div>
        <div class="clearfix"></div>

        <form id="lancamento" data-parsley-validate class="form-horizontal form-label-left" NAME="lancamento"  ACTION="{$SCRIPT_NAME}" METHOD="post">
            <input name=mod                 type=hidden value="fin">   
            <input name=form                type=hidden value="banco">   
            <input name=submenu             type=hidden value={$subMenu}>
            <input name=letra               type=hidden value={$letra}>

            
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>
                        {if $subMenu eq "cadastrar"}
                        {else}
                        {/if} 
                        {if $mensagem neq ''}
                            {if $tipoMsg eq 'sucesso'}
                                <div class="row">
                                    <div class="col-lg-12 text-left">
                                        <div>
                                            <div class="alert alert-success" role="alert"><strong>--Sucesso!</strong>&nbsp;{$mensagem}</div>
                                        </div>
                                    </div>
                                </div>
                            {elseif $tipoMsg eq 'alerta'}
                                <div class="row">
                                    <div class="col-lg-12 text-left">
                                        <div>
                                            <div class="alert alert-danger" role="alert"><strong>--Aviso!</strong>&nbsp;{$mensagem}</div>
                                        </div>
                                    </div>
                                </div>       
                            {/if}

                        {/if}
                    </h2>
                    
                    <ul class="nav navbar-right panel_toolbox">
                        <li><button type="button" class="btn btn-primary"  onClick="javascript:submitConfirmar('conta_banco');">
                                <span class="glyphicon glyphicon-export" aria-hidden="true"></span><span> Confirmar</span></button>
                        </li>
                        <li><button type="button" class="btn btn-danger"  onClick="javascript:submitVoltar('conta_banco');">
                                <span class="glyphicon glyphicon-export" aria-hidden="true"></span><span> Cancelar</span></button>
                        </li>
                      <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                      </li>
                      <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                      </li>
                      <li><a class="close-link"><i class="fa fa-close"></i></a>
                      </li>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <br />
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="banco">Empresa <span class="required">*</span>
                        </label>
                        <div class="col-md-3 col-sm-6 col-xs-12">
                                <select class="form-control" name="banco" id="banco">
                                    {html_options values=$banco_ids selected=$banco_id output=$banco_names}
                                </select>
                        </div>
                      </div>
                                
                      <div class="form-group">
                        <label class="control-label col-md-6 col-sm-3 col-xs-12" for="banco">
                            <h3>Financeiro</h3>
                        </label>
                      </div>
                                
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="banco">Genêro <span class="required">*</span>
                        </label>
                        <div class="col-md-3 col-sm-6 col-xs-12">
                                <select class="form-control" name="banco" id="banco">
                                    {html_options values=$banco_ids selected=$banco_id output=$banco_names}
                                </select>
                        </div>
                      </div>

                               
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nomeInterno">Conta Bancária <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input id="nomeInterno" name="nomeInterno" type="text" required="required" 
                                 class="form-control col-md-7 col-xs-12" 
                                 placeholder="Conta bancária padrão para a Empresa." value={$nomeInterno}>
                        </div>
                      </div>
                        
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nomeInterno">Modo Recebimento <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input id="nomeInterno" name="nomeInterno" type="text" required="required" 
                                 class="form-control col-md-7 col-xs-12" 
                                 placeholder="Modo recebimento padrão para a Empresa." value={$nomeInterno}>
                        </div>
                      </div>
                        
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nomeInterno">Tipo Recebimento <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input id="nomeInterno" name="nomeInterno" type="text" required="required" 
                                 class="form-control col-md-7 col-xs-12" 
                                 placeholder="Tipo recebimento padrão para a Empresa." value={$nomeInterno}>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-6 col-sm-3 col-xs-12" for="banco">
                            <h3>Estoque</h3>
                        </label>
                      </div>

                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="banco">Natureza de Operação <span class="required">*</span>
                        </label>
                        <div class="col-md-3 col-sm-6 col-xs-12">
                                <select class="form-control" name="banco" id="banco">
                                    {html_options values=$banco_ids selected=$banco_id output=$banco_names}
                                </select>
                        </div>
                      </div>
                                
                      <div class="form-group">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="id">CFOP<span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input id="id" name="id" type="text" required="required" 
                                 class="form-control col-md-7 col-xs-12" 
                                 placeholder="CFOP padrão para emissão de NF de saída." value={$id}>
                        </div>
                      </div>
                    
                        
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nomeInterno">Controla Estoque <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input id="nomeInterno" name="nomeInterno" type="text" required="required" 
                                 class="form-control col-md-7 col-xs-12" 
                                 placeholder="Controla estoque para a Empresa." value={$nomeInterno}>
                        </div>
                      </div>
                        
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nomeInterno">Integra Financeiro <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input id="nomeInterno" name="nomeInterno" type="text" required="required" 
                                 class="form-control col-md-7 col-xs-12" 
                                 placeholder="Integra financeiro na emissão de NFe para a Empresa." value={$nomeInterno}>
                        </div>
                      </div>
                        
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nomeInterno">Autorização Nfe <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <input id="nomeInterno" name="nomeInterno" type="text" required="required" 
                                 class="form-control col-md-7 col-xs-12" 
                                 placeholder="Autorização NFe automátiamente junto a receita na emissão do pedido para a Empresa." value={$nomeInterno}>
                        </div>
                      </div>

                      <div class="form-group">
                        <label class="control-label col-md-6 col-sm-3 col-xs-12" for="banco">
                            <h3>Inteligência Artificial</h3>
                        </label>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="agencia">Padrão de Classificação: <span class="required">*</span>
                        </label>
                        <div class="col-md-1 col-sm-6 col-xs-12">
                          <input id="agencia" name="agencia" type="text" required="required" 
                                 class="form-control col-md-7 col-xs-12" 
                                 tittle="Digite o código da agência sem o digito verificador." value={$agencia}>
                        </div>
                        <label class="control-label col-md-2 col-sm-3 col-xs-12" for="contaCorrente">Padrão de Treinamento:<span class="required">*</span>
                        </label>
                        <div class="col-md-1 col-sm-6 col-xs-12">
                          <input id="contaCorrente" name="contaCorrente" type="text" required="required" 
                                 class="form-control col-md-7 col-xs-12" 
                                 tittle="Conta Corrente no Banco, utilize o formato 99999-9." value={$contaCorrente}>
                        </div>
                        <label class="control-label col-md-2 col-sm-3 col-xs-12" for="contaCorrente">Padrão de Regressão:<span class="required">*</span>
                        </label>
                        <div class="col-md-1 col-sm-6 col-xs-12">
                          <input id="contaCorrente" name="contaCorrente" type="text" required="required" 
                                 class="form-control col-md-7 col-xs-12" 
                                 tittle="Conta Corrente no Banco, utilize o formato 99999-9." value={$contaCorrente}>
                        </div>
                      </div>
                        
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="agencia">Horário de treinamento: <span class="required">(Hs)</span>
                        </label>
                        <div class="col-md-1 col-sm-6 col-xs-12">
                          <input id="agencia" name="agencia" type="text" required="required" 
                                 class="form-control col-md-7 col-xs-12" 
                                 tittle="Digite o código da agência sem o digito verificador." value={$agencia}>
                        </div>
                        <label class="control-label col-md-2 col-sm-3 col-xs-12" for="contaCorrente">Confirma Opção Única:<span class="required">*</span>
                        </label>
                        <div class="col-md-1 col-sm-6 col-xs-12">
                                <select class="form-control" name="banco" id="banco">
                                    {html_options values=$banco_ids selected=$banco_id output=$banco_names}
                                </select>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="contaCorrente">Aplica Semelhança de Perfil:<span class="required">*</span>
                        </label>
                        <div class="col-md-1 col-sm-6 col-xs-12">
                                <select class="form-control" name="banco" id="banco">
                                    {html_options values=$banco_ids selected=$banco_id output=$banco_names}
                                </select>
                        </div>
                        <label class="control-label col-md-2 col-sm-3 col-xs-12" for="contaCorrente">Algoritmo SVM:<span class="required">*</span>
                        </label>
                        <div class="col-md-1 col-sm-6 col-xs-12">
                                <select class="form-control" name="banco" id="banco">
                                    {html_options values=$banco_ids selected=$banco_id output=$banco_names}
                                </select>
                        </div>
                      </div>

                      <div class="form-group">
                          <div class="x_content" class="col-md-5 col-sm-6 col-xs-6" >
                            <table id="datatable-buttons" class="table table-bordered jambo_table">
                                <thead>
                                    <tr class="headings">
                                        <th>Situação</th>
                                        <th>Alarme</th>
                                        <th>|</th>
                                        <th>Rotina</th>
                                        <th>Alarme</th>
                                    </tr>
                                </thead>

                                <tbody>
                                        <tr class="even pointer">
                                            <td> Situação 1 </td>
                                            <td> Alarme 1 </td>
                                            <td> | </td>
                                            <td> Rotina 1 </td>
                                            <td> Alarme 1 </td>
                                        </tr>
                                        <tr class="even pointer">
                                            <td> Situação 2 </td>
                                            <td> Alarme 2 </td>
                                            <td> | </td>
                                            <td> Rotina 2</td>
                                            <td> Alarme 2 </td>
                                        </tr>
                                </tbody>

                            </table>

                      </div> <!-- div class="x_content" = inicio tabela -->
                                
                                
                      <div class="ln_solid"></div>
                        
                  </div>
                </div>
              </div>
            </div>
        </form>

      </div>

    {include file="template/form.inc"}  
                    