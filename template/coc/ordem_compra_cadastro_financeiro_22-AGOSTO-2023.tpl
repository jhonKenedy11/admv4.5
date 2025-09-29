<script type="text/javascript" src="{$pathJs}/coc/s_ordem_compra.js"> </script>
    <!-- page content -->
    <div class="right_col" role="main">      
      <div class="">

        <div class="page-title">
          <div class="title_left">
              <h3>Ordem Compra</h3>
          </div>
        </div>
        <div class="clearfix"></div>
        <form id="lancamento" data-parsley-validate  class="form-horizontal form-label-left" NAME="lancamento"  
              ACTION="{$SCRIPT_NAME}" METHOD="post">
            <input name=mod           type=hidden value="coc">   
            <input name=form          type=hidden value="ordem_compra">   
            <input name=opcao         type=hidden value="{$opcao}">   
            <input name=submenu       type=hidden value={$subMenu}>
            <input name=letra         type=hidden value={$letra}>
            <input name=id            type=hidden value={$id}>
            <input name=cliente       type=hidden value={$cliente}>
            <input name=pessoa        type=hidden value={$pessoa}>
            <input name=fornecedor    type=hidden value=''>
            <input name=descCondPgto  type=hidden value="{$descCondPgto}">
            <input name=condPgto_id   type=hidden value="{$condPgto_id}">
            <input name=manter        type=hidden value="{$manter}">
            <input name=nf            type=hidden value="{$nf}">
            <input name=serie         type=hidden value="{$serie}">
            <input name=dadosFinanceiros    type=hidden value={$dadosFinanceiros}>
            <input name=itenscotacao    type=hidden value={$itenscontacao}>
            <input name=obs    type=hidden value={$obs}>
            
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>
                        Cadastro de Nota fiscal e Financeiro
                        {if $mensagem neq ''}
                                <div class="alert alert-error" role="alert">&nbsp;{$mensagem}</div>
                        {/if}
                    </h2>

                    
                    <ul class="nav navbar-right panel_toolbox">
                        <li><button type="button" class="btn btn-primary"  onClick="javascript:submitCadastraNf({$id});">
                                <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span><span> Confirmar</span></button>
                        </li>
                        <li><button type="button" class="btn btn-danger"  onClick="javascript:submitVoltar('');">
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
                            <label for="nf">N&uacute;mero NF</label>
                            <div class="panel panel-default">
                                <input maxlength="11" type="text" class="form-control" id="numNf" name="numNf" value="{$numNf}">
                            </div>
                        </div>
                        
                        <div class="col-md-1 col-sm-1 col-xs-1">
                            <label for="serie">Serie</label>
                            <div class="panel panel-default">
                                <input maxlength="3" class="form-control" type="text" id="serie" name="serie" value={$serie}>
                            </div>
                        </div>
                        
                        <div class="col-md-3 col-sm-3 col-xs-3  has-feedback">
                                <label for="dataEntrada">Data Entrada:</label>
                                <input class="form-control has-feedback-left" type="text" id="dataEntrada" name="dataEntrada" 
                                       required="required" value={$dataEntrada} data-inputmask="'mask': '99/99/9999'">
                                <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>                                
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

                        
                        <div class="col-md-2 col-sm-2 col-xs-2">
                            <label for="condPgto">Condi&ccedil;&atilde;o de Pagamento</label>
                            <div class="panel panel-default">
                                <select name="condPgto" class="form-control" <!--onChange="javascript:submitAtual({$id});"-->>
                                    {html_options values=$condPgto_ids selected=$condPgto_id output=$condPgto_names}
                                </select>
                            </div>
                        </div>
                        
                        </h5>    
                    </div>

                    <div class="row">
                        <div class="col-md-5 col-sm-5 col-xs-5">
                                <label for="clienteNome">Cliente</label>
                                <div class="panel panel-default">
                                    <input type="text" class="form-control" id="clienteNome" name="clienteNome" disabled value="{$clienteNome}">
                                </div>
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-3">
                            <label for="idNatop">Natureza Opera&ccedil;&atilde;o</label>
                            <div class="panel panel-default">
                                    <select id="natop" name="natop" class="form-control">
                                        {html_options values=$natOperacao_ids selected=$natOperacao_id output=$natOperacao_names}
                                    </select>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-2 col-xs-2">
                            <label for="centroCusto">Centro de Custo</label>
                            <div class="panel panel-default">
                                <select name="centroCusto" class="form-control" <!--onChange="javascript:submitAtual({$id});"-->>
                                    {html_options values=$centroCusto_ids selected=$centroCusto_id output=$centroCusto_names}
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-2 col-xs-2">
                            <label for="genero">G&ecirc;nero</label>
                            <div class="panel panel-default">
                                <select name="genero" class="form-control">
                                    {html_options values=$genero_ids selected=$genero_id output=$genero_names}
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2 col-sm-2 col-xs-2">
                            <label for="basest">Base ST</label>
                            <div class="panel panel-default">
                                <input type="text" class="form-control money" id="basest" name="basest" value="{$basest}">
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-2 col-xs-2">
                            <label for="st">Sub Tributária</label>
                            <div class="panel panel-default">
                                <input type="text" class="form-control money"  id="st" name="st" value="{$st}">
                            </div>
                        </div>
                        
                        <div class="col-md-4 col-sm-4 col-xs-4">
                            <label for="nfeReferenciada">Nfe Referenciada</label>
                            <div class="input-group">
                                <input maxlength="44" class="form-control" size="50px" type="text" name="nfeReferenciada" value={$nfeReferenciada}>
                            </div>
                        </div>

                        <div class="col-md-3 col-sm-3 col-xs-3  has-feedback">
                                <label for="dataEmissao">Data Emissão:</label>
                                <input class="form-control has-feedback-left" type="text" 
                                       id="dataEmissao" name="dataEmissao" 
                                       required="required" value={$dataEmissao} data-inputmask="'mask': '99/99/9999'">
                                <span class="fa fa-calendar-o form-control-feedback left" aria-hidden="true"></span>                                
                        </div>
                        
                    </di>
                </div>
               
                <div class="x_panel">
                    <div class="" role="tabpanel" data-example-id="togglable-tabs">
                      <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                        <li role="presentation" class="active"><a href="#tab_content1" id="parcelas-tab" role="tab" data-toggle="tab" aria-expanded="true">Parcelas</a>
                        </li>
                        <li role="presentation"><a href="#tab_content2" id="itens-tab" role="tab" data-toggle="tab" aria-expanded="true">Itens</a>
                        </li>
                        <li role="presentation"><a href="#tab_content3" id="obs-tab" role="tab" data-toggle="tab" aria-expanded="true">Observações</a>
                        </li>
                      </ul>
                      <div id="myTabContent" class="tab-content">
                        <div role="tabpanel" class="tab-pane fade active in" id="tab_content1" aria-labelledby="home-tab">

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

                                        {section name=i loop=$fin}
                                            {assign var="total" value=$total+1}
                                            <tr>
                                                <td> {$fin[i].PARCELA} </td>
                                                <td> 
                                                    <input class="form-control" type="text" id="venc" name="venc{$fin[i].PARCELA}" value={$fin[i].VENCIMENTO|date_format:"%d/%m/%Y"} >
                                                </td>
                                                <td> 
                                                    <input class="form-control" type="text" id="valor" name="valor{$fin[i].PARCELA}" value={$fin[i].VALOR|number_format:2:",":"."}>

                                                </td>
                                                <td>
                                                    <select id="idTipoDoc" name="tipo{$fin[i].PARCELA}" class="form-control">
                                                        {html_options values=$tipoDocto_ids selected=$tipoDocto_id output=$tipoDocto_names}
                                                    </select>
                                                </td>
                                                <td>
                                                    <select id="idConta" name="conta{$fin[i].PARCELA}" class="form-control">
                                                        {html_options values=$conta_ids selected=$conta_id output=$conta_names}
                                                    </select>
                                                </td>
                                                <td> 
                                                    <select id="idSitucao" name="situacao{$fin[i].PARCELA}" class="form-control">
                                                        {html_options values=$situacaoLanc_ids selected=$situacaoLanc_id output=$situacaoLanc_names}
                                                    </select>
                                                </td>
                                                <td> 
                                                    
                                                    
                                                    <input class="form-control" type="text" id="obs" name="obs{$fin[i].PARCELA}" value={$fin[i].OBS}>

                                                </td>
                                            </tr>
                                        <p>
                                    {/section} 

                                    </tbody>
                                </table>
                              </div>       

                        </div>
                        <div role="tabpanel" class="tab-pane fade small" id="tab_content2" aria-labelledby="profile-tab">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <!--table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap table-condensed" cellspacing="0" width="100%"-->
                                <table id="datatable-buttons-2" class="table table-bordered jambo_table">
                                    <thead>
                                        <tr style="background: gray; color: white;">
                                            <th>NrItem</th>
                                            <th>Quant</th>
                                            <th>Unitario</th>
                                            <th>Total</th>
                                            <th>CFOP</th>
                                            <th>TRIB Icms</th>
                                            <th>Aliq IPI</th>
                                            <th>IPI</th>                                         
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {section name=i loop=$lancItens}
                                            <tr>
                                                <td> {$lancItens[i].NRITEM} </td>
                                                <td> {$lancItens[i].QTSOLICITADA|number_format:2:",":"."} </td>
                                                <td> {$lancItens[i].UNITARIO|number_format:2:",":"."} </td>
                                                <td> {$lancItens[i].TOTAL|number_format:2:",":"."} </td>
                                                <td><input class="form-control" type="text" id="cfop"     name = "cfop{$lancItens[i].NRITEM}"     value={5102} maxlength=4></td>
                                                <td><input class="form-control" type="text" id="tribicms" name = "tribicms{$lancItens[i].NRITEM}" value={"000"} maxlength=3></td>
                                                <td><input class="form-control money" type="text" id="aliqipi"  name = "aliqipi{$lancItens[i].NRITEM}"  value={0|number_format:2:",":"."}></td>
                                                <td><input class="form-control money" type="text" id="ipi"      name = "ipi{$lancItens[i].NRITEM}"      value={0|number_format:2:",":"."}></td>
                                            </tr>
                                            <p>
                                        {/section} 
                                    </tbody>
                                </table>
                              </div>       

                        </div>
                        <div role="tabpanel" class="tab-pane fade small" id="tab_content3" aria-labelledby="profile-tab">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <label for="obs" >Observa&ccedil;&atilde;o</label>
                                <textarea class="resizable_textarea form-control" id="obs" name="obs" rows="3" >{$obs}</textarea>
                            </div> 
                        </div>
                    </div>
                </div> <!-- tabpanel -->
            </div> <!-- panel -->


                                
              <div class="ln_solid"></div>
                        
              </div>
            </div>
        </form>

      </div>

    {include file="template/form.inc"} 

    <script src="https://cdn.rawgit.com/plentz/jquery-maskmoney/master/dist/jquery.maskMoney.min.js"></script> 
    <script>
      $(document).ready(function(){
        $(".money").maskMoney({            
         decimal: ",",
         thousands: ".",
         allowNegative: true,
         allowZero: true
        });        
     });
    </script>   

    <script>
      $(function() {
        $('#dataEntrada').daterangepicker({
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

    <script>
      $(function() {
        $('#dataEmissao').daterangepicker({
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

    