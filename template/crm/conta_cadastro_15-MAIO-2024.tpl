    <script type="text/javascript" src="{$pathJs}/bib/s_default.js"> </script>
    <script type="text/javascript" src="{$pathJs}/crm/s_conta.js"> </script>
        <!-- page content -->
        <div class="right_col" role="main">      
      <div class="">

        <div class="page-title">
          <div class="title_left">
              <h3>Pessoas</h3>
          </div>
        </div>
        <div class="clearfix"></div>
        <form id="lancamento" data-parsley-validate  class="form-horizontal form-label-left" NAME="lancamento"  
              ACTION="{$SCRIPT_NAME}" METHOD="post">
            <input name=mod                 type=hidden value="crm">   
            <input name=form                type=hidden value="contas">   
            <input name=opcao               type=hidden value={$opcao}>
            <input name=submenu             type=hidden value={$subMenu}>
            <input name=id                  type=hidden value={$id}>
            <input name=letra               type=hidden value={$letra}>
            <input id="tipo" name=tipo      type=hidden value={$tipo}> <!-- tipo endereco -->
            
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>
                        {if $subMenu eq "cadastrar"}
                            Cadastro 
                        {else}
                            Altera&ccedil;&atilde;o 
                        {/if} 
                        {include file="../bib/msg.tpl"}
                    </h2>
                    
                    <ul class="nav navbar-right panel_toolbox">
                        <li><button type="submit" id="btnSubmit" class="btn btn-primary"  onClick="javascript:submitConfirmar();">
                                <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span><span> Confirmar</span></button>
                        </li>
                        <li><button type="button" class="btn btn-danger"  onClick="javascript:submitVoltarConta('{$opcao}');">
                                <span class="glyphicon glyphicon-remove" aria-hidden="true"></span><span> Cancelar</span></button>
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
                        <form class="container" novalidate="" action="/echo" method="POST" id="myForm">
                        <div class="form-group">
                            <div class="col-md-4 col-sm-12 col-xs-12">
                                <label for="nomeReduzido" >Nome</label>
                                <input class="form-control border-blue"  required="true" maxlength="20" type="text" id="nomeReduzido" name="nomeReduzido" placeholder="Digite o nome Reduzido." title="Digite o nome Reduzido." value={$nomeReduzido}>
                            </div>  
                        </div>

                        <div class="form-group">
                            <div class="col-md-2 col-sm-12 col-xs-12">
                                <label for="pessoa">Tipo Pessoa</label>
                                <SELECT class="form-control" name="pessoa" required="true"> 
                                    {html_options values=$pessoa_ids output=$pessoa_names selected=$pessoa_id}
                                </SELECT>
                            </div>  

                            <div class="col-md-3 col-sm-12 col-xs-12 ">
                                <label for="cnpjCpf">CNPJ/CPF</label>
                                <input class="form-control" type="number" id="cnpjCpf" name="cnpjCpf" placeholder="Digite somente numeros." title="Digite CNPJ/CPF somente numeros."
                                    onblur="showHint(this.value);" value={$cnpjCpf} onKeyPress="if(this.value.length==14) return false;">
                            </div>
                            
                            <div class="col-md-3 col-sm-12 col-xs-12">
                                <label for="ieRg">Insc. Estadual/R.G.</label>
                                <input class="form-control" type="number" id="ieRg" name="ieRg" placeholder="Digite somente numeros." 
                                    title="Digite o RG/IE somente numeros." value={$ieRg} onKeyPress="if(this.value.length==15) return false;">
                            </div>  
                            <div class="col-md-4 col-sm-12 col-xs-12">
                                <label for="im">Insc. Municipal</label>
                                <input class="form-control" type="number" onKeyPress="if(this.value.length==14) return false;" id="im" name="im" placeholder="Digite somente numeros." 
                                title="Digite a IM somente numeros." value={$im}>
                            </div>  
                        </div>

                        <div class="form-group">
                            <div class="col-md-8 col-sm-12 col-xs-12">
                                <label for="nome">Raz&atilde;o Social / Nome Completo</label>
                                <input class="form-control" maxlength="50"  required="required" type="text" id="nome" name="nome" placeholder="Digite o nome completo." title="Digite o nome completo." value={$nome}>
                            </div>  
                            <div class="col-md-4 col-sm-12 col-xs-12">
                                <label for="contato">Contato</label>
                                <input class="form-control" maxlength="15" type="text" id="contato" name="contato" placeholder="Digite o contato." value={$contato}>
                            </div>
                        </div>

                            <span class="section"><h4>Endere&ccedil;o</h4></span>

                        <div class="form-group">
                            <div class="col-md-2 col-sm-6 col-xs-6">
                                <input class="form-control has-feedback-left"  required="required" maxlength="9" type="text"  data-inputmask="'mask' : '99999-999'"
                                       id="cep" name="cep" placeholder="Cep" onblur="pesquisacep(this.value);" value={$cep} >
                                <span class="fa fa-envelope form-control-feedback left" aria-hidden="true"></span>
                            </div>  

                            <div class="col-md-6 col-sm-6 col-xs-6">
                                <input class="form-control has-feedback-left" required="true" maxlength="60" type="text" id="endereco" name="endereco" placeholder="Endereço." value={$endereco}>
                                <span class="glyphicon glyphicon-home form-control-feedback left" aria-hidden="true"></span>
                            </div>  
                            <div class="col-md-1 col-sm-6 col-xs-6">
                                <input class="form-control" type="text" onKeyPress="if(this.value.length==7) return false;" required="true" id="numero" name="numero" placeholder="Numero" value={$numero}>
                            </div>  
                            <div class="col-md-3 col-sm-6 col-xs-6">
                                <input class="form-control" maxlength="15" type="text" id="complemento" name="complemento" placeholder="Complemento" value={$complemento}>
                            </div>  
                        </div>

                        <div class="form-group">

                            <div class="col-md-3 col-sm-6 col-xs-6">
                                <input class="form-control" maxlength="60" type="text" id="bairro" name="bairro" placeholder="Bairro" value={$bairro} required="true">
                            </div>  
                            <div class="col-md-6 col-sm-6 col-xs-6">
                                <input class="form-control" maxlength="40" type="text" id="cidade" name="cidade" placeholder="Cidade." value={$cidade} required="true">
                            </div>  
                            <div class="col-md-3 col-sm-6 col-xs-6">
                                <SELECT class="form-control" required="true" name="estado" id="estado"> 
                                    {html_options values=$estado_ids output=$estado_names selected=$estado_id}
                                </SELECT>
                            </div>  
                        </div>
                        
                        <div class="form-group">

                            <div class="col-md-6 col-sm-6 col-xs-6">
                                <input type="text" class="form-control has-feedback-left" 
                                       placeholder="Email" id="email" name="email" value={$email}>
                                <span class="fa fa-at form-control-feedback left" aria-hidden="true"></span>
                            </div>  

                            <div class="col-md-3 col-sm-6 col-xs-6">
                                <input type="text" class="form-control has-feedback-left" data-inputmask="'mask' : '(99) 9999-9999'" 
                                       id="fone" placeholder="Fone" name="fone" value={$fone}>
                                <span class="fa fa-phone form-control-feedback left" aria-hidden="true"></span>
                            </div>  

                            <div class="col-md-3 col-sm-6 col-xs-6">
                                <input type="text" class="form-control has-feedback-left" data-inputmask="'mask' : ['(99) 99999-9999', '(99) 99999-9999'], 'keepStatic': 'true'"
                                        id="celular" name="celular" placeholder="Celular" value={$celular}>
                                <span class="glyphicon glyphicon-phone form-control-feedback left" aria-hidden="true"></span>
                            </div>  
                        </div>
                            
                  </div>
                </div>
                                    
                    <!-- dados adicionaris -->                
                    <!-- start accordion -->
                    <div class="accordion" id="accordion" role="tablist" aria-multiselectable="true">
                      <div class="panel">
                        <a class="panel-heading collapsed" role="tab" id="headingTwo" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                          <h4 class="panel-title">Dados Adicionais <i class="fa fa-chevron-down"></i>
                          </h4>
                        </a>
                        <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                            <div class="panel-body">
                                <div class="" role="tabpanel" data-example-id="togglable-tabs">
                                    <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                                        <li role="presentation" class="active"><a href="#tab_content1" id="dados-cliente-tab" role="tab" data-toggle="tab" aria-expanded="true">Dados Clientes</a>
                                        </li>
                                        <li role="presentation" class=""><a href="#tab_content2" role="tab" id="dados-credito-tab" data-toggle="tab" aria-expanded="true">Créditos</a>
                                        </li>
                                        <li role="presentation" class=""><a href="#tab_content3" id="referencia-cliente-tab" role="tab" data-toggle="tab" aria-expanded="true">Ponto Referência</a>
                                        </li>
                                        <li role="presentation" class=""><a href="#tab_content4" id="tributos-tab" role="tab" data-toggle="tab" aria-expanded="true">Tributos</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        <div id="myTabContent" class="tab-content">
                            <div role="tabpanel" class="tab-pane fade active in" id="tab_content1" aria-labelledby="home-tab">
                                <div class="panel-body">
                                    <div class="x_panel">
                                        <div class="form-group">
                                            <div class="col-md-3 col-sm-6 col-xs-6">
                                                <label for="classe">Classe</label>
                                                <SELECT class="form-control" name="classe"> 
                                                    {html_options values=$classe_ids output=$classe_names selected=$classe_id}
                                                </SELECT>
                                            </div>  

                                            <div class="col-md-3 col-sm-6 col-xs-6">
                                                <label for="atividade">Atividade</label>
                                                    <SELECT class="form-control" name="atividade"> 
                                                        {html_options values=$atividade_ids output=$atividade_names selected=$atividade_id}
                                                    </SELECT>
                                                </div>  

                                            <div class="col-md-3 col-sm-6 col-xs-6">
                                                <label for="vendedor">Responsável</label>
                                                <SELECT class="form-control" name="vendedor"> 
                                                    {html_options values=$responsavel_ids output=$responsavel_names selected=$responsavel_id}
                                                </SELECT>
                                            </div>
                                            <div class="col-md-3 col-sm-6 col-xs-6">
                                                <label for="vendedor">Filial</label>
                                                <SELECT class="form-control" name="filial"> 
                                                    {html_options values=$filial_ids output=$filial_names selected=$filial_id}
                                                </SELECT>
                                            </div>                                                    
                                        </div>

                                        <div class="form-group">
                                            <div class="col-md-2 col-sm-12 col-xs-12  has-feedback">
                                                <label for="datanascimento">Data Nascimento</label>
                                                <input class="form-control" type="text" size="15" 
                                                    placeholder="Ex:01/01/2020" id="dataNascimento" name="dataNascimento" data-inputmask="'mask':'99/99/9999'" 
                                                    value={$dataNascimento}>                               
                                            </div>

                                            <div class="col-md-2 col-sm-12 col-xs-12">
                                                <label for="codMunicipio">C&oacute;digo do Municipio</label>
                                                <input class="form-control" maxlength="7" type="text" id="codMunicipio" name="codMunicipio" 
                                                    readonly placeholder="Numero" value={$codMunicipio} required="true">
                                            </div>  
                                            
                                            
                                        
                                            <!--div class="col-md-4 col-sm-12 col-xs-12">
                                                <label for="numMatricula">C&oacute;digo Empresa</label>
                                                <input class="form-control" maxlength="10" type="text" id="numMatricula" name="numMatricula" placeholder="Digite somente numeros." title="C&oacute;digo interno de controle da Empresa." value={$numMatricula}>
                                            </div-->  
                                        </div>
                                        
                                        <div class="form-group">
                                            
                                            <div class="col-md-6 col-sm-6 col-xs-6">
                                                <label for="homePage">Home Page</label>
                                                <div class="form-group input-group">
                                                    <span class="input-group-btn">
                                                        <button class="btn btn-default" type="button"><i class="fa fa-unlink"></i></button>
                                                    </span>
                                                    <input class="form-control" type="url" id="homePage" name="homePage" placeholder="http://www.admservice.com.br." value={$homePage}>
                                                </div>
                                            </div>  

                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                <label for="obs" >Observa&ccedil;&atilde;o</label>
                                                <textarea class="resizable_textarea form-control" id="obs" name="obs" rows="3" >{$obs}</textarea>
                                            </div>  
                                        </div>
                                        <div class="form-group">
                                            <div class="col-md-6 col-sm-6 col-xs-6">
                                                <label for="suframa">Usu&aacute;rio Login</label>
                                                <input class="form-control" maxlength="30" type="text" name="userLogin" placeholder="Usu&aacute;rio utilizado para acesso externo." value={$userLogin}>
                                            </div>  
                                            <div class="col-md-6 col-sm-6 col-xs-6">
                                                <label for="suframa">Senha Login</label>
                                                <input class="form-control" maxlength="30" type="password" name="senhaLogin" placeholder="Senha utilizada para acesso externo."  value={$senhaLogin}>
                                            </div>  
                                        </div>
                                        
                              
                                        
                                    </div> <!--FIM class="x_panel" -->
                                </div> <!--FIM class="panel-body" -->
                            </div> <!--FIM class="tab-pane fade active in" -->
                            
                            
                            <div role="tabpanel" class="tab-pane fade small" id="tab_content2" aria-labelledby="profile-tab">
                                <div class="panel-body">
                                    <div class="x_panel">
                                        <table id="datatable-cc" class="table table-bordered jambo_table col-md-8">
                                            <thead>
                                                <tr style="background: gray; color: white;">
                                                <th width="10%">Pedido</th>
                                                <th width="10%">Nr Item</th>
                                                <th width="10%">Quantidade</th>
                                                <th width="15%">Unitario</th>
                                                <th width="15%">Valor</th>
                                                <th width="20%">Valor Utilizado</th>
                                                <th width="20%">PED Utilizado</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            {assign var="total" value="0"}
                                            {assign var="utilizado" value="0"}                                                            
                                            {section name=i loop=$credito}
                                            {assign var="valorTotal" value=$valorTotal+$credito[i].VALOR}
                                            {assign var="valorUtilizado" value=$valorUtilizado+$credito[i].UTILIZADO}

                                                <tr>
                                                    <td name="pedido"> {$credito[i].PEDIDO} </td>
                                                    <td name="nritem"> {$credito[i].NRITEM} </td>
                                                    <td name="quantidade"> {$credito[i].QUANTIDADE} </td>
                                                    <td name="unitario"> {$credito[i].UNITARIO} </td>
                                                    <td name="valor"> {$credito[i].VALOR} </td> 
                                                    <td name="credutilizado"> {$credito[i].UTILIZADO} </td>
                                                    <td name="pedutilizado"> {$credito[i].PEDIDOUTILIZADO} </td>                                                      
                                                </tr>
                                                <p>
                                            {/section}
                                            <tr>
                                                <td>Totais</td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td>R$ {$valorTotal|number_format:2:",":"."}</td>
                                                <td>R$ {$valorUtilizado|number_format:2:",":"."}</td>                                                
                                                <td></td>
                                            </tr> 
                                            </tbody>
                                        </table>
                                    </div> <!-- FIM class="x_panel" --> 
                                </div> <!-- FIM class="panel-body" --> 
                            </div> <!-- FIM class="tab-pane fade small" --> 

                            <!-- TAB 3 -->
                            <div role="tabpanel" class="tab-pane fade small" id="tab_content3" aria-labelledby="profile-tab">
                                <div class="panel-body">
                                    <div class="x_panel">
                                        <div class="form-group">
                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                <label for="referencia" >Ponto de Referência</label>
                                                <textarea class="resizable_textarea form-control" id="referencia" name="referencia" rows="3" >{$referencia}</textarea>
                                            </div>  
                                        </div>
                                        <div class="form-group">
                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                <label for="transversal1" >Transversal1</label>
                                                <textarea class="resizable_textarea form-control" id="transversal1" name="transversal1" rows="3" >{$transversal1}</textarea>
                                            </div>  
                                        </div>
                                        <div class="form-group">
                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                <label for="transversal2" >Transversal2</label>
                                                <textarea class="resizable_textarea form-control" id="transversal2" name="transversal2" rows="3" >{$transversal2}</textarea>
                                            </div>  
                                        </div>
                                    </div> <!-- FIM class="x_panel" --> 
                                </div> <!-- FIM class="panel-body" --> 
                            </div> <!-- FIM class="tab-pane fade small" --> 

                            <!-- TAB 4--> 
                            <div role="tabpanel" class="tab-pane fade small" id="tab_content4" aria-labelledby="profile-tab">
                                <div class="panel-body">
                                    <div class="x_panel">
                                        <div class="form-group">
                                            <div class="col-md-2 col-sm-12 col-xs-12">
                                                <label for="suframa">Suframa</label>
                                                <input class="form-control" type="number" id="suframa" name="suframa" placeholder="Digite somente numeros." title="C&oacute;digo Suframa." 
                                                    value={$suframa} onKeyPress="if(this.value.length==10) return false;">
                                            </div>  
                                            <div class="col-md-2 col-sm-12 col-xs-12">
                                                <label for="limiteCredito">Limite Cr&eacute;dito</label>
                                                <input class="form-control money" type="money" id="limiteCredito" name="limiteCredito" placeholder="Digite somente numeros." title="Limite de cr&eacute;dito venda." 
                                                    value={$limiteCredito} maxlength="10">
                                            </div>  
                                        </div>
                                        <div class="form-group">
                                            <div class="col-md-2 col-sm-12 col-xs-12">
                                                <label for="regimeEspecialST">Regime Esp. ST</label>
                                                <select class="form-control" name="regimeEspecialST" id="regimeEspecialST" title="Regime especial ST.">
                                                    {html_options values=$boolean_ids selected=$regimeEspecialST output=$boolean_names}
                                                </select>
                                            </div>  
                                                                            
                                            <div class="col-md-2 col-sm-12 col-xs-12">
                                                <label for="regimeEspecialSTMT">Regime especial ST MT</label>
                                                <select class="form-control" name="regimeEspecialSTMT" id="regimeEspecialSTMT" title="Regime especial ST MT.">
                                                    {html_options values=$boolean_ids selected=$regimeEspecialSTMT output=$boolean_names}
                                                </select>
                                            </div>  
                                    
                                            <div class="col-md-2 col-sm-12 col-xs-12">
                                                <label for="contribuinteICMS">Contribuinte de ICMS</label>
                                                <select class="form-control" name="contribuinteICMS" id="contribuinteICMS" title="Contribuinte de ICMS.">
                                                    {html_options values=$boolean_ids selected=$contribuinteICMS output=$boolean_names}
                                                </select>
                                            </div>  
                                    
                                            <div class="col-md-2 col-sm-12 col-xs-12">
                                                <label for="consumidorFinal">Consumidor Final</label>
                                                <select class="form-control" name="consumidorFinal" id="consumidorFinal" title="Consumidor final.">
                                                    {html_options values=$boolean_ids selected=$consumidorFinal output=$boolean_names}
                                                </select>
                                            </div>  

                                            <div class="col-md-2 col-sm-12 col-xs-12">
                                                <label for="regimeEspecialSTMTAliq">Regime esp ST MT Aliq</label>
                                                <input class="form-control money" type="money" id="regimeEspecialSTMTAliq" name="regimeEspecialSTMTAliq" 
                                                    value={$regimeEspecialSTMTAliq} maxlength="9">                                     
                                            </div>   
                                    
                                            <div class="col-md-2 col-sm-12 col-xs-12">
                                                <label for="regimeEspecialSTAliq">Regime esp ST Aliq</label>
                                                <input class="form-control money" type="money" id="regimeEspecialSTAliq" name="regimeEspecialSTAliq" 
                                                    value={$regimeEspecialSTAliq} maxlength="9">                                     
                                            </div> 

                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                <label for="regimeEspecialSTMsg">Regime Esp. ST MSg</label>
                                                <textarea class="resizable_textarea form-control" id="regimeEspecialSTMsg" name="regimeEspecialSTMsg" rows="3" >{$regimeEspecialSTMsg}</textarea>
                                            </div> 
                                        </div>
                                        <div class="form-group">
                                            <div class="col-md-6 col-sm-6 col-xs-6">
                                                <label for="emailNfe">Email Nfe</label>
                                                <div class="form-group input-group">
                                                    <span class="input-group-btn">
                                                        <button class="btn btn-default" type="button"><i class="fa fa-at"></i> </button>
                                                    </span>
                                                    <input type="email" class="form-control" maxlength="45" 
                                                    placeholder="Email Nfe" id="emailNfe" name="emailNfe" value={$emailNfe}>
                                                </div>
                                            </div>  
                                        </div>
                                        
                                    </div> <!-- FIM class="x_panel" --> 
                                </div> <!-- FIM class="panel-body" --> 
                            </div> <!-- FIM class="tab-pane fade small" --> 


                        </div> <!-- FIM id="myTabContent" -->  
                        
                    </div>
                </div> <!-- tabpanel -->   


                           
                          </div>
                        </div>
                      </div>
                    </div>
                </form>
                    <!-- end of accordion -->
                                    
                                    
              </div>
            </div>

                        
        </form>
      </div>

    {include file="template/form.inc"} 
    <script src="https://cdn.rawgit.com/plentz/jquery-maskmoney/master/dist/jquery.maskMoney.min.js"></script>
    <script>
          $(document).ready
    (function(){
            $(".money").
    maskMoney({            
         decimal: ",",
         thousands: ".",
         allowNegative: true
        });        
     });

    $(function(){
        $('#dataNascimento').daterangepicker({
          singleDatePicker: true,
          calender_style: "picker_1",
          locale: {
                format: 'DD/MM/YYYY',
                daysOfWeek: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
                monthNames: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
            }
          
        }); 
    });
    </script>
    <style type="text/css">
        input[type="number"]::-webkit-outer-spin-button, 
        input[type="number"]::-webkit-inner-spin-button {
          -webkit-appearance: none;
           margin: 0;
        }
        input[type="number"] {
        -moz-appearance: textfield;
        }
        .form-control{
          border-radius: 5px;
        }
      </style>