<script type="text/javascript" src="{$pathJs}/ped/s_pedido_venda_nf_ps.js"> </script>
    <!-- page content -->
    <div class="right_col" role="main">      
      <div class="">

        <div class="page-title">
          <div class="title_left">
              <h3>Pedido</h3>
          </div>
        </div>
        <div class="clearfix"></div>
        <form id="lancamento" data-parsley-validate  class="form-horizontal form-label-left" NAME="lancamento"  
              ACTION="{$SCRIPT_NAME}" METHOD="post">
            <input name=mod           type=hidden value="{$mod}">   
            <input name=form          type=hidden value="{$form}">   
            <input name=opcao         type=hidden value="{$opcao}">   
            <input name=submenu       type=hidden value={$subMenu}>
            <input name=letra         type=hidden value={$letra}>
            <input name=id            type=hidden value={$id}>
            <input name=cliente       type=hidden value={$cliente}>
            <input name=pessoa        type=hidden value={$pessoa}>
            <input name=fornecedor    type=hidden value=''>
            <input name=descCondPgto  type=hidden value="{$descCondPgto}">
            <input name=alteraCondPgto  type=hidden value="{$alteraCondPgto}">



            
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>
                    {if $formNf eq true}
                        Cadastro de Nota fiscal e Financeiro
                        {if $mensagem neq ''}
                                <div class="alert alert-warning" role="alert">&nbsp;{$mensagem}</div>
                                <!--div class="checkbox">
                                    <input type="checkbox" class="flat" name="nfAberto" value="false"> Confirma cadastro NF em ABERTO? confime novamente.
                                </div-->
                        {/if}
                    {else}
                        Cadastro de Parcelas do Serviço
                        {if $mensagem neq ''}
                                <div class="alert alert-warning" role="alert">&nbsp;{$mensagem}</div>
                        {/if}
                    {/if}
                    </h2>

                    
                    <ul class="nav navbar-right panel_toolbox">
                        {if $formNf eq true}
                            <li><button type="button" class="btn btn-primary" onClick="javascript:submitCadastraNf('{$id}');" >
                            <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span><span> Confirmar Nf</span></button> 
                            </li>
                        {else}
                            {if $parcelasCadastrada neq true}
                                <li><button type="button" class="btn btn-primary" onClick="javascript:submitCadastraFinanceiro('{$id}');">
                                <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span><span> Confirmar Serviços</span></button> 
                                </li>
                            {/if}
                        {/if}
                        <li><button type="button" class="btn btn-danger"  onClick="javascript:submitVoltarNovo('{$opcao}');">
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
                <div class="x_content small">
                    <div class="row">
                        <h5>
                        <div class="col-md-2 col-sm-2 col-xs-2">
                            <label for="pedido">N&uacute;mero do Pedido</label>
                            <div class="panel panel-default">
                                <input type="text" class="form-control" id="pedido" name="pedido" disabled value="{$pedido}">
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-2 col-xs-2">
                            <label for="data">Data</label>
                            <div class="panel panel-default">
                                <input type="text" class="form-control" id="data" name="data" disabled value="{$data}">
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-2 col-xs-2 ">
                            <label for="total">T O T A L</label>
                            <div class="panel panel-default left_col">
                                <input class="form-control" type="text" id="total" name="total" readonly value={$total}>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-6">
                            <label for="clienteNome">Cliente</label>
                        <div class="panel panel-default">
                                <input type="text" class="form-control" id="clienteNome" name="clienteNome" disabled value="{$clienteNome}">
                            </div>
                        </div>
                        </h5>    
                    </div>

                    <div class="row">
                        <div class="col-md-1 col-sm-1 col-xs-1">
                            <label for="serie">Serie</label>
                            <div class="panel panel-default">
                                <input class="form-control" type="text" id="serie" name="serie" value={$serie}>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-4">
                            <label for="idNatop">Natureza Opera&ccedil;&atilde;o</label>
                            <div class="panel panel-default">
                                    <select id="idNatop" name="idNatop" class="form-control">
                                        {html_options values=$natOperacao_ids selected=$natOperacao_id output=$natOperacao_names}
                                    </select>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-2 col-xs-2">
                            <label for="condPgto">Condi&ccedil;&atilde;o de Pagamento</label>
                            <div class="panel panel-default">
                                <select name="condPgto" class="form-control" onChange="javascript:submitAtual({$id}, 'true');">
                                    {html_options values=$condPgto_ids selected=$condPgto_id output=$condPgto_names}
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-2 col-xs-2">
                            <label for="centroCusto">Centro de Custo</label>
                            <div class="panel panel-default">
                                <select name="centroCusto" class="form-control" onChange="javascript:submitAtual({$id});">
                                    {html_options values=$centroCusto_ids selected=$centroCusto_id output=$centroCusto_names}
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-3">
                            <label for="genero">G&ecirc;nero</label>
                            <div class="panel panel-default">
                                <select name="genero" class="form-control">
                                    {html_options values=$genero_ids selected=$genero_id output=$genero_names}
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
               
                <div class="x_panel">
                    <div class="" role="tabpanel" data-example-id="togglable-tabs">
                      <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                        <li role="presentation" class="active"><a href="#tab_content1" id="parcelas-tab" role="tab" data-toggle="tab" aria-expanded="true">Parcelas Serviço</a>
                        </li>
                        <!--li role="presentation" class=""><a href="#tab_content2" id="parcelas-servico-tab" role="tab" data-toggle="tab" aria-expanded="false">Parcelas Serviço</a>
                        </li-->
                      </ul>
                      <div id="myTabContent" class="tab-content">
                        <div role="tabpanel" class="tab-pane fade active in" id="tab_content1" aria-labelledby="home-tab">
                            <div class="col-md-3 col-sm-2 col-xs-2">
                                    <label for="numNf">Num Nota fiscal</label>
                                    <div class="panel panel-default">
                                        <input type="text" class="form-control" id="numNf" name="numNf" placeholder="Numero Nf"  value="{$numNf}">
                                    </div>
                                </div>
                        <!-- panel tabela dados -->  
                              <div class="col-md-12 col-sm-12 col-xs-12">
                                <!--table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap table-condensed" cellspacing="0" width="100%"-->
                                <table id="datatable-buttons-1" class="table table-bordered jambo_table">
                                     <thead>
                                        <tr style="background: gray; color: white;">
                                            <th>Parcela</th>
                                            <th>Data Vencimento</th>
                                            <th>Valor</th>
                                            <th>Tipo Documento</th>
                                            <th>Conta Recebimento</th>
                                            <th>Situa&ccedil;&atilde;o Lan&ccedil;amento</th>
                                            <th>Obs</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        {section name=i loop=$finServico}
                                            {assign var="total" value=$total+1}
                                            <tr >
                                                <td> {$finServico[i].PARCELA} </td>
                                                <td> 
                                                    <input {if $parcelasCadastrada eq true} readonly {/if} class="form-control" type="text" id="vencServico" name="vencServico{$finServico[i].PARCELA}" value={$finServico[i].VENCIMENTO|date_format:"%d/%m/%Y"} >
                                                </td>
                                                <td> 
                                                    <input {if $parcelasCadastrada eq true} readonly {/if} class="form-control" type="text" id="valorServico" name="valorServico{$finServico[i].PARCELA}" value={$finServico[i].VALOR|number_format:2:",":"."}>

                                                </td>
                                                <td>
                                                    <select id="idTipoDocServico" name="tipoServico{$finServico[i].PARCELA}" class="form-control {if $parcelasCadastrada eq true} select-read-only {/if}">
                                                        {html_options values=$tipoDocto_ids selected=$tipoDocto_id output=$tipoDocto_names}
                                                    </select>
                                                </td>
                                                <td>
                                                    <select  id="idContaServico" name="contaServico{$finServico[i].PARCELA}" class="form-control {if $parcelasCadastrada eq true} select-read-only {/if}">
                                                        {html_options values=$conta_ids selected=$conta_id output=$conta_names}
                                                    </select>
                                                </td>
                                                <td> 
                                                    <select id="idSitucaoServico" name="situacaoServico{$finServico[i].PARCELA}" class="form-control {if $parcelasCadastrada eq true} select-read-only {/if}">
                                                        {html_options values=$situacaoLanc_ids selected=$situacaoLanc_id output=$situacaoLanc_names}
                                                    </select>
                                                </td>
                                                <td> 
                                                    
                                                    
                                                    <input {if $parcelasCadastrada eq true} readonly {/if} class="form-control" type="text" id="obs" name="obsServico{$finServico[i].PARCELA}" value={$finServico[i].OBS}>

                                                </td>
                                            </tr>
                                        <p>
                                    {/section} 

                                    </tbody>
                                </table>
                              </div>       

                        </div>
                        <!--div role="tabpanel" class="tab-pane fade " id="tab_content2">
                                <div class="col-md-3 col-sm-2 col-xs-2">
                                    <label for="numNf">Num Nota fiscal</label>
                                    <div class="panel panel-default">
                                        <input type="text" class="form-control" id="numNf" name="numNf" placeholder="Numero Nf"  value="{$numNf}">
                                    </div>
                                </div-->
                        <!-- panel tabela dados -->  
                              <!--div class="col-md-12 col-sm-12 col-xs-12"-->
                                <!--table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap table-condensed" cellspacing="0" width="100%"-->
                                <!--table id="datatable-buttons-2" class="table table-bordered jambo_table">
                                    <thead>
                                        <tr style="background: gray; color: white;">
                                            <th>Parcela</th>
                                            <th>Data Vencimento</th>
                                            <th>Valor</th>
                                            <th>Tipo Documento</th>
                                            <th>Conta Recebimento</th>
                                            <th>Situa&ccedil;&atilde;o Lan&ccedil;amento</th>
                                            <th>Obs</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        {section name=i loop=$finServico}
                                            {assign var="total" value=$total+1}
                                            <tr >
                                                <td> {$finServico[i].PARCELA} </td>
                                                <td> 
                                                    <input {if $parcelasCadastrada eq true} readonly {/if} class="form-control" type="text" id="vencServico" name="vencServico{$finServico[i].PARCELA}" value={$finServico[i].VENCIMENTO|date_format:"%d/%m/%Y"} >
                                                </td>
                                                <td> 
                                                    <input {if $parcelasCadastrada eq true} readonly {/if} class="form-control" type="text" id="valorServico" name="valorServico{$finServico[i].PARCELA}" value={$finServico[i].VALOR|number_format:2:",":"."}>

                                                </td>
                                                <td>
                                                    <select id="idTipoDocServico" name="tipoServico{$finServico[i].PARCELA}" class="form-control {if $parcelasCadastrada eq true} select-read-only {/if}">
                                                        {html_options values=$tipoDocto_ids selected=$tipoDocto_id output=$tipoDocto_names}
                                                    </select>
                                                </td>
                                                <td>
                                                    <select  id="idContaServico" name="contaServico{$finServico[i].PARCELA}" class="form-control {if $parcelasCadastrada eq true} select-read-only {/if}">
                                                        {html_options values=$conta_ids selected=$conta_id output=$conta_names}
                                                    </select>
                                                </td>
                                                <td> 
                                                    <select id="idSitucaoServico" name="situacaoServico{$finServico[i].PARCELA}" class="form-control {if $parcelasCadastrada eq true} select-read-only {/if}">
                                                        {html_options values=$situacaoLanc_ids selected=$situacaoLanc_id output=$situacaoLanc_names}
                                                    </select>
                                                </td>
                                                <td> 
                                                    
                                                    
                                                    <input {if $parcelasCadastrada eq true} readonly {/if} class="form-control" type="text" id="obs" name="obsServico{$finServico[i].PARCELA}" value={$finServico[i].OBS}>

                                                </td>
                                            </tr>
                                        <p>
                                    {/section} 

                                    </tbody>
                                </table>
                              </div-->       

                        </div>
                        
            </div> <!-- panel -->


                                
              <div class="ln_solid"></div>
                        
              </div>
            </div>
        </form>

      </div>

    {include file="template/form.inc"}  

    <style> 
        .select-read-only{
            background: #eee; 
            pointer-events: none;
            touch-action: none;
        }
    </style>