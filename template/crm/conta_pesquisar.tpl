<style>
.form-control, .x_panel {
    border-radius: 5px;
}
</style>
    <script type="text/javascript" src="{$pathJs}/crm/s_conta.js"> </script>
        <!-- page content -->
        <div class="right_col" role="main">      
        <div class="">

            <div class="row">


              <!-- panel principal  -->  
              <div class="col-md-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Pessoa - Pesquisar</h2>
                        
                    <ul class="nav navbar-right panel_toolbox">
                        <li><button type="button" class="btn btn-warning"  onClick="javascript:submitLetra();">
                                <span class="glyphicon glyphicon-zoom-in" aria-hidden="true"></span><span> Pesquisa</span>
                            </button>
                        </li>
                        <li><button type="button" class="btn btn-primary"  onClick="javascript:submitCadastro('pesquisar');">
                                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span><span> Cadastro</span>
                            </button>
                        </li>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                <!--div class="x_content" style="display: none;"-->
                <div class="x_content">

                    <form id="lancamento" name="lancamento" data-parsley-validate METHOD="POST"
                          class="form-horizontal form-label-left" ACTION={$SCRIPT_NAME}>
                        <input name=mod           type=hidden value="{$mod}">   
                        <input name=form          type=hidden value="{$form}">   
                        <input name=id            type=hidden value="">
                        <input name=opcao         type=hidden value={$opcao}>
                        <input name=letra         type=hidden value={$letra}>
                        <input name=submenu       type=hidden value={$subMenu}>
                        <input name=credito       type=hidden value={$credito}>

                        <div class="form-group col-md-8 col-sm-12 col-xs-12">
                            <label>Pessoa</label>
                            <input class="form-control" id="pesNome" name="pesNome" placeholder="Digite o nome do Pessoa."  value={$pesNome} >
                        </div>

                        <div class="form-group col-md-4 col-sm-12 col-xs-12">
                            <label>CNPJ ou CPF</label>
                            <input  class="form-control" type="text" id="pesCnpjCpf" name="pesCnpjCpf" placeholder="Digite o CNPJ/CPF."   value={$pesCnpjCpf}>
                        </div>    

                  </div>
                        
                        
                    <!-- dados adicionaris -->                
                    <!-- start accordion -->
                    <div class="accordion" id="accordion" role="tablist" aria-multiselectable="true">
                      <div class="panel">
                        <a class="panel-heading collapsed" role="tab" id="headingTwo" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                          <h4 class="panel-title">Filtros Adicionais <i class="fa fa-chevron-down"></i>
                          </h4>
                        </a>
                        <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                          <div class="panel-body">
                            <div class="x_panel">

                        <div class="form-group col-md-5 col-sm-12 col-xs-12">
                            <input  class="form-control" type="text" id="pesCidade" name="pesCidade" placeholder="Digite a cidade."   value={$cidade}>
                        </div>
                        <div class="form-group col-md-3 col-sm-12 col-xs-12">
                            <SELECT  class="form-control" name="idEstado"> 
                                {html_options values=$estado_ids output=$estado_names selected=$estado_id}
                            </SELECT>
                        </div>
                        <div class="clearfix"></div>

                        <div class="form-group col-md-3 col-sm-12 col-xs-12">
                            <SELECT class="form-control" name="idVendedor"> 
                                {html_options values=$responsavel_ids output=$responsavel_names selected=$responsavel_id}
                            </SELECT>
                        </div>
                            
                            
                        <div class="form-group col-md-3 col-sm-12 col-xs-12">
                            <SELECT class="form-control"  name="idAtividade"> 
                                {html_options values=$atividade_ids output=$atividade_names selected=$atividade_id}
                            </SELECT>
                        </div>
                            
                        <div class="form-group col-md-3 col-sm-12 col-xs-12">
                            <SELECT class="form-control" name="idClasse"> 
                                {html_options values=$classe_ids output=$classe_names selected=$classe_id}
                            </SELECT>
                        </div>

                        <div class="form-group col-md-3 col-sm-12 col-xs-12">
                            <SELECT class="form-control" name="idPessoa"> 
                                {html_options values=$tipoPessoa_ids output=$tipoPessoa_names selected=$tipoPessoa_id}
                            </SELECT>
                        </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- end of accordion -->

                    </form>

                </div>
                          
            </div> <!-- div class="x_content" = inicio tabela -->
        </div> <!-- div class="x_panel" = painel principal-->



        <!-- panel tabela dados -->  
        <div class="col-md-12 col-xs-12">
            <div class="x_panel">
                <!--table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap table-condensed" cellspacing="0" width="100%"-->
                <table id="datatable-buttons1" class="table table-bordered jambo_table">
                    <thead>
                        <tr style="background: #2A3F54; color: white;">
                            <th>Nome</th>
                            <th>Nome Reduzido</th>
                            <th>Cidade</th>
                            <th>Telefone</th>
                            <th>Classe</th>
                            <th style="width: 40px;">Pesquisa</th>

                        </tr>
                    </thead>
                    <tbody>

                        {section name=i loop=$lanc}
                            {assign var="total" value=$total+1}
                            <tr>
                                <td> {$lanc[i].NOME} </td>
                                <td> {$lanc[i].NOMEREDUZIDO} </td>
                                <td> {$lanc[i].CIDADE} - {$lanc[i].UF} </td>
                                <td> {$lanc[i].FONEAREA} {$lanc[i].FONE} / {$lanc[i].FAXAREA} {$lanc[i].FAX} </td>
                                <td> {$lanc[i].BLOQUEADO} </td>
                                
                                <td class=" last">
                                    {if $opcao == 'pesquisarAtendimento' }

                                        <button 
                                            type="button" 
                                            class="btn btn-success btn-xs" 
                                            onclick="javascript:fechaPesquisaAtendimento('{$lanc[i].CLIENTE}', '{$lanc[i].NOME}','{$lanc[i].FONECONTATO}');">
                                            <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
                                        </button>

                                    {elseif $opcao == 'pesquisarRelatorios'}
                                        
                                        <button 
                                            type="button" 
                                            class="btn btn-success btn-xs" 
                                            onclick="javascript:fechaPesquisaRelatorios('{$lanc[i].CLIENTE}', '{$lanc[i].NOME}');">
                                            <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
                                        </button>
                                    
                                    {else}

                                        {if $lanc[i].BLOQUEADO neq 'BLOQUEADO'}
                                            <button type="button" class="btn btn-success btn-xs" 
                                            onclick="javascript:fechaLancamento('{$lanc[i].CLIENTE}', '{$lanc[i].NOME}', '{$opcao}' , '{$lanc[i].CREDITO|number_format:2:",":"."}', '{$lanc[i].CEP}', '{$lanc[i].CODMUNICIPIO}', '{$lanc[i].BLOQUEADO}', '{$lanc[i].ID_REPRESENTANTE}' );">
                                            <span class="glyphicon glyphicon-ok" aria-hidden="true"></span></button>
                                        {/if}

                                    {/if}
                                    
                                </td>
                            </tr>
                        {/section} 

                    </tbody>
                </table>

              </div> <!-- div class="x_content" = inicio tabela -->
            </div> <!-- div class="x_panel" = painel principal-->
          </div> <!-- div class="col-md-12 col-sm-12 col-xs-12 "-->
        </div> <!-- div class="row "-->



    {include file="template/database.inc"}  
    
    <!-- /Datatables -->